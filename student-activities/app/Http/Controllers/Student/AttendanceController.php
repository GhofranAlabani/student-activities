<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * تسجيل الحضور عبر QR Code
     */
public function checkInWithQR(Request $request)
{
    // نقبل qr_data أو activity_id مباشرة
    $request->validate([
        'qr_data' => 'nullable|string',
        'activity_id' => 'nullable|integer|exists:activities,id',
    ]);

    $activityId = null;
    
    // إذا فيه activity_id مباشر
    if ($request->has('activity_id')) {
        $activityId = $request->activity_id;
    }
    // إذا فيه qr_data
    elseif ($request->has('qr_data')) {
        $qrData = $request->qr_data;
        
        // استخراج activity_id من الرابط
        if (preg_match('/activity_id=(\d+)/', $qrData, $matches)) {
            $activityId = $matches[1];
        }
    }

    if (!$activityId) {
        return response()->json([
            'success' => false,
            'message' => 'QR Code غير صحيح!'
        ], 400);
    }

    // بقية الكود...
    $activity = Activity::find($activityId);
    
    if (!$activity) {
        return response()->json([
            'success' => false,
            'message' => 'النشاط غير موجود'
        ], 404);
    }

    if ($activity->status !== 'مفتوح') {
        return response()->json([
            'success' => false,
            'message' => 'هذا النشاط غير مفتوح للحضور'
        ], 400);
    }

    $user = Auth::user();
    $isRegistered = $activity->registrations()
        ->where('student_id', $user->id)
        ->exists();

    if (!$isRegistered) {
        return response()->json([
            'success' => false,
            'message' => 'يجب أن تكون مسجلاً في النشاط أولاً'
        ], 400);
    }

    $existingRecord = \App\Models\AttendanceRecord::where('user_id', $user->id)
        ->where('activity_id', $activity->id)
        ->first();

    if ($existingRecord) {
        return response()->json([
            'success' => false,
            'message' => 'لقد سجلت حضورك مسبقاً في هذا النشاط'
        ], 400);
    }

    $pointsEarned = $activity->points ?? 10;

    \App\Models\AttendanceRecord::create([
        'user_id' => $user->id,
        'activity_id' => $activity->id,
        'check_in_time' => now(),
        'status' => 'present',
        'points_earned' => $pointsEarned,
    ]);

    $user->increment('total_points', $pointsEarned);
    $user->increment('activities_completed', 1);

    if (class_exists('\App\Models\PointsHistory')) {
        \App\Models\PointsHistory::create([
            'user_id' => $user->id,
            'activity_id' => $activity->id,
            'points' => $pointsEarned,
            'reason' => 'حضور نشاط: ' . $activity->title,
        ]);
    }

    // إرسال إيميل تأكيد
    // try {
//     \Mail::to($user->email)->send(new \App\Mail\AttendanceConfirmationMail(
//         $user, 
//         $activity, 
//         $pointsEarned,
//         now()
//     ));
//     \Log::info('✅ Attendance email sent to: ' . $user->email);
// } catch (\Exception $e) {
//     \Log::error('❌ Failed to send attendance email: ' . $e->getMessage());
// }

    return response()->json([
        'success' => true,
        'message' => 'تم تسجيل الحضور بنجاح!',
        'points' => $pointsEarned,
        'total_points' => $user->total_points
    ]);
}
    /**
     * تسجيل الحضور يدوياً
     */
    public function manualCheckIn(Activity $activity)
    {
        $result = $this->attendanceService->checkIn(Auth::user(), $activity, 'manual');

        if ($result['success']) {
            return back()->with('success', 'تم تسجيل الحضور بنجاح! +' . $result['record']->points_earned . ' نقطة');
        }

        return back()->with('error', $result['message']);
    }

    /**
     * عرض سجل الحضور للطالب
     */
    public function myAttendance()
    {
        $attendanceRecords = Auth::user()->attendanceRecords()
            ->with('activity')
            ->orderBy('check_in_time', 'desc')
            ->paginate(20);

        $totalPresent = Auth::user()->attendanceRecords()
            ->where('status', 'present')
            ->count();

        $totalActivities = Auth::user()->activities()->count();

        $attendanceRate = $totalActivities > 0 ? ($totalPresent / $totalActivities) * 100 : 0;

        return view('student.attendance.index', compact(
            'attendanceRecords',
            'totalPresent',
            'totalActivities',
            'attendanceRate'
        ));
    }
    /**
 * عرض صفحة مسح QR Code
 */
public function scanPage(Request $request)
{
    // إذا فيه activity_id في الرابط، نمرره للـ View
    $activityId = $request->query('activity_id');
    
    return view('student.attendance.scan', compact('activityId'));
}
}