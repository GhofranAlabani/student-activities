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
        $request->validate([
            'qr_data' => 'required|string',
        ]);

        $result = $this->attendanceService->verifyQRCode($request->qr_data, Auth::user());

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الحضور بنجاح!',
                'points' => $result['record']->points_earned,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
        ], 400);
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
public function scanPage()
{
    return view('student.attendance.scan');
}
}