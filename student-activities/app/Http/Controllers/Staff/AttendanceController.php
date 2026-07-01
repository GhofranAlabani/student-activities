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
        if ($activity->supervisor_id !== Auth::id()) {
            abort(403, 'هذا النشاط ليس تحت إشرافك');
        }

        $stats = $this->attendanceService->getAttendanceStats($activity);
        $attendanceRecords = $activity->attendanceRecords()
            ->with('user')
            ->orderBy('check_in_time', 'desc')
            ->get();

        return view('staff.attendance.index', compact('activity', 'stats', 'attendanceRecords'));
    }

    /**
 * عرض QR Code للنشاط
 */
public function showQR(Activity $activity)
{
    if ($activity->supervisor_id !== Auth::id()) {
        abort(403, 'هذا النشاط ليس تحت إشرافك');
    }

    // توليد بيانات QR
    $qrData = [
        'activity_id' => $activity->id,
        'token' => md5($activity->id . $activity->created_at),
        'expires_at' => now()->addHours(2)->timestamp,
    ];
    
    $qrString = json_encode($qrData);
    
    // استخدام API مجاني لتوليد QR Code
    $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrString);

    return view('staff.attendance.qr', compact('activity', 'qrCodeUrl', 'qrString'));
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
 
}