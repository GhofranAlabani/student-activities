<?php

namespace App\Http\Controllers\Staff;

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
     * عرض صفحة الحضور للنشاط
     */
    public function index(Activity $activity)
    {
        // التحقق من صلاحية المشرف
        if ($activity->supervisor_id !== Auth::id()) {
            abort(403, 'هذا النشاط ليس تحت إشرافك');
        }

        // جلب سجلات الحضور
        $attendanceRecords = $activity->attendanceRecords()
            ->with('user')
            ->orderBy('check_in_time', 'desc')
            ->get();

        // حساب الإحصائيات
        $totalPresent = $attendanceRecords->where('status', 'present')->count();
        $totalActivities = $activity->registrations()->count();
        $attendanceRate = $totalActivities > 0 ? ($totalPresent / $totalActivities) * 100 : 0;

        // إحصائيات إضافية
        $stats = [
            'total_registered' => $totalActivities,
            'total_present' => $totalPresent,
            'total_absent' => $totalActivities - $totalPresent,
            'attendance_rate' => round($attendanceRate, 1),
        ];

        return view('staff.attendance.index', compact(
            'activity',
            'stats',
            'attendanceRecords',
            'totalPresent',
            'totalActivities',
            'attendanceRate'
        ));
    }

    /**
     * عرض QR Code للنشاط
     */
   /**
 * عرض QR Code للنشاط
 */
public function showQR(Activity $activity)
{
    // التحقق من صلاحية المشرف
    if ($activity->supervisor_id !== Auth::id()) {
        abort(403, 'هذا النشاط ليس تحت إشرافك');
    }

    // توليد رابط بسيط وواضح (الرابط اللي يفتحه الطالب)
    $qrData = route('attendance.scan') . '?activity_id=' . $activity->id;
    
    // استخدام API مجاني لتوليد QR Code
    $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=' . urlencode($qrData);

    return view('staff.attendance.qr', compact('activity', 'qrCodeUrl', 'qrData'));
}
    /**
     * تسجيل الحضور يدوياً
     */
    public function manualCheckIn(Request $request, Activity $activity)
    {
        if ($activity->supervisor_id !== Auth::id()) {
            abort(403, 'هذا النشاط ليس تحت إشرافك');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = \App\Models\User::find($request->user_id);
        $result = $this->attendanceService->checkIn($user, $activity, 'manual');

        if ($result['success']) {
            return back()->with('success', 'تم تسجيل الحضور بنجاح');
        }

        return back()->with('error', $result['message']);
    }

    /**
     * تصدير تقرير الحضور
     */
    public function exportReport(Activity $activity)
    {
        if ($activity->supervisor_id !== Auth::id()) {
            abort(403, 'هذا النشاط ليس تحت إشرافك');
        }

        $attendanceRecords = $activity->attendanceRecords()
            ->with('user')
            ->orderBy('check_in_time')
            ->get();

        return view('staff.attendance.export', compact('activity', 'attendanceRecords'));
    }

  /**
 * التحقق من QR Code وتسجيل الحضور
 */
public function checkInQR(Request $request)
{
    $activityId = null;

    // محاولة 1: إذا كان activity_id موجود مباشرة
    if ($request->has('activity_id')) {
        $activityId = $request->activity_id;
    }
    // محاولة 2: إذا كان qr_data (JSON أو رابط)
    elseif ($request->has('qr_data')) {
        $qrData = $request->qr_data;
        
        // محاولة استخراج activity_id من الرابط
        if (preg_match('/activity_id=(\d+)/', $qrData, $matches)) {
            $activityId = $matches[1];
        }
        // محاولة فك JSON
        else {
            $decoded = json_decode($qrData, true);
            if ($decoded && isset($decoded['activity_id'])) {
                $activityId = $decoded['activity_id'];
            }
        }
    }

    // إذا ما لقينا activity_id
    if (!$activityId) {
        return response()->json([
            'success' => false,
            'message' => 'QR Code غير صحيح! تأكد من مسح الكود الصحيح.'
        ], 400);
    }

    // جلب النشاط
    $activity = Activity::find($activityId);

    if (!$activity) {
        return response()->json([
            'success' => false,
            'message' => 'النشاط غير موجود'
        ], 404);
    }

    // التحقق من حالة النشاط
    if ($activity->status !== 'مفتوح') {
        return response()->json([
            'success' => false,
            'message' => 'هذا النشاط غير مفتوح للحضور'
        ], 400);
    }

    // التحقق من أن الطالب مسجل في النشاط
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

    // التحقق من عدم تسجيل الحضور مسبقاً
    $existingRecord = \App\Models\AttendanceRecord::where('user_id', $user->id)
        ->where('activity_id', $activity->id)
        ->first();

    if ($existingRecord) {
        return response()->json([
            'success' => false,
            'message' => 'لقد سجلت حضورك مسبقاً في هذا النشاط'
        ], 400);
    }

    // حساب النقاط
    $pointsEarned = $activity->points ?? 10;

    // إنشاء سجل الحضور
    \App\Models\AttendanceRecord::create([
        'user_id' => $user->id,
        'activity_id' => $activity->id,
        'check_in_time' => now(),
        'status' => 'present',
        'points_earned' => $pointsEarned,
    ]);

    // تحديث نقاط الطالب
    $user->increment('total_points', $pointsEarned);
    $user->increment('activities_completed', 1);

    // إضافة سجل في points_history
    \App\Models\PointsHistory::create([
        'user_id' => $user->id,
        'activity_id' => $activity->id,
        'points' => $pointsEarned,
        'reason' => 'حضور نشاط: ' . $activity->title,
    ]);

    // التحقق من الشارات الجديدة
    $this->checkAndAwardBadges($user);

    // إنشاء إشعار
    \App\Models\Notification::create([
        'user_id' => $user->id,
        'type' => 'attendance_marked',
        'title' => '✅ تم تسجيل الحضور',
        'message' => "تم تسجيل حضورك في \"{$activity->title}\" وحصلت على {$pointsEarned} نقطة",
        'icon' => '✅',
        'color' => '#10b981',
        'is_read' => false,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'تم تسجيل الحضور بنجاح!',
        'activity' => $activity->title,
        'points' => $pointsEarned,
        'total_points' => $user->total_points
    ]);
}

/**
 * فحص ومنح الشارات للطالب
 */
private function checkAndAwardBadges($user)
{
    $badges = \App\Models\Badge::where('is_active', true)->get();
    
    foreach ($badges as $badge) {
        // تحقق إذا الطالب عنده الشارة بالفعل
        if ($user->badges()->where('badge_id', $badge->id)->exists()) {
            continue;
        }

        $qualified = false;

        switch ($badge->type) {
            case 'activities':
                $qualified = $user->activities_completed >= $badge->requirement;
                break;
            case 'points':
                $qualified = $user->total_points >= $badge->requirement;
                break;
            case 'attendance':
                $attendanceCount = \App\Models\AttendanceRecord::where('user_id', $user->id)
                    ->where('status', 'present')
                    ->count();
                $qualified = $attendanceCount >= $badge->requirement;
                break;
        }

        if ($qualified) {
            $user->badges()->attach($badge->id, ['earned_at' => now()]);

            // إنشاء إشعار للشارة
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'type' => 'badge_earned',
                'title' => '🏆 مبروك! حصلت على شارة جديدة',
                'message' => "حصلت على شارة \"{$badge->name}\" - {$badge->description}",
                'icon' => $badge->icon ?? '🏆',
                'color' => $badge->color ?? '#d4a017',
                'is_read' => false,
            ]);
        }
    }
}
}