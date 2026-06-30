<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\AttendanceRecord;
use App\Models\User;
use Carbon\Carbon;

class AttendanceService
{
    /**
     * تسجيل الحضور
     */
    public function checkIn(User $user, Activity $activity, $method = 'manual', $latitude = null, $longitude = null)
    {
        // التحقق من أن النشاط يسمح بالحضور
        if (!$activity->attendance_enabled) {
            return ['success' => false, 'message' => 'الحضور غير مفعّل لهذا النشاط'];
        }

        // التحقق من وقت الحضور
        $now = Carbon::now();
        $checkInStart = $activity->check_in_start ? Carbon::parse($activity->check_in_start) : null;
        $checkInEnd = $activity->check_in_end ? Carbon::parse($activity->check_in_end) : null;

        if ($checkInStart && $now->lt($checkInStart)) {
            return ['success' => false, 'message' => 'لم يبدأ وقت الحضور بعد'];
        }

        if ($checkInEnd && $now->gt($checkInEnd)) {
            return ['success' => false, 'message' => 'انتهى وقت الحضور'];
        }

        // التحقق من الموقع الجغرافي (إذا مطلوب)
        if ($activity->location_latitude && $activity->location_longitude && $latitude && $longitude) {
            $distance = $this->calculateDistance(
                $activity->location_latitude,
                $activity->location_longitude,
                $latitude,
                $longitude
            );

            if ($distance > $activity->location_radius) {
                return ['success' => false, 'message' => 'أنت بعيد عن موقع النشاط'];
            }
        }

        // تسجيل الحضور
        $record = AttendanceRecord::updateOrCreate(
            ['activity_id' => $activity->id, 'user_id' => $user->id],
            [
                'check_in_time' => $now,
                'status' => 'present',
                'check_in_method' => $method,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'points_earned' => $activity->points ?? 10,
            ]
        );

        // تحديث نقاط الطالب
        $user->increment('total_points', $activity->points ?? 10);

        return ['success' => true, 'record' => $record];
    }

    /**
     * حساب المسافة بين نقطتين (Haversine formula)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // بالمتر

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * توليد QR Code للنشاط
     */
    public function generateQRCode(Activity $activity)
    {
        $qrData = [
            'activity_id' => $activity->id,
            'token' => md5($activity->id . $activity->created_at),
            'expires_at' => $activity->date->addHours(2)->timestamp,
        ];

        return json_encode($qrData);
    }

    /**
     * التحقق من QR Code
     */
    public function verifyQRCode($qrData, User $user)
    {
        $data = json_decode($qrData, true);

        if (!$data || !isset($data['activity_id'])) {
            return ['success' => false, 'message' => 'QR Code غير صالح'];
        }

        $activity = Activity::find($data['activity_id']);

        if (!$activity) {
            return ['success' => false, 'message' => 'النشاط غير موجود'];
        }

        if ($data['expires_at'] < time()) {
            return ['success' => false, 'message' => 'QR Code منتهي الصلاحية'];
        }

        return $this->checkIn($user, $activity, 'qr');
    }

    /**
     * الحصول على إحصائيات الحضور
     */
    public function getAttendanceStats(Activity $activity)
    {
        $totalRegistered = $activity->users()->count();
        $present = AttendanceRecord::where('activity_id', $activity->id)
            ->where('status', 'present')
            ->count();
        $absent = $totalRegistered - $present;
        $attendanceRate = $totalRegistered > 0 ? ($present / $totalRegistered) * 100 : 0;

        return [
            'total_registered' => $totalRegistered,
            'present' => $present,
            'absent' => $absent,
            'attendance_rate' => round($attendanceRate, 2),
        ];
    }
}