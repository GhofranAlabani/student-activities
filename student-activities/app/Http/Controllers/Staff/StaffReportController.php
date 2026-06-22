<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Registration;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffReportController extends Controller
{
    public function show(Activity $activity)
    {
        if ($activity->supervisor_id !== Auth::id()) {
            abort(403, 'هذا النشاط ليس تحت إشرافك');
        }

        $activity->load(['activityType', 'registrations.user']);

        // إحصائيات
        $totalRegistrations = $activity->registrations->count();
        $approvedRegistrations = $activity->registrations->where('status', 'approved')->count();
        $pendingRegistrations = $activity->registrations->where('status', 'pending')->count();
        
        // التقييمات
        $ratings = Rating::where('activity_id', $activity->id)->get();
        $avgRating = $ratings->avg('rating') ?? 0;
        $ratingsCount = $ratings->count();

        return view('staff.reports.show', compact(
            'activity',
            'totalRegistrations',
            'approvedRegistrations',
            'pendingRegistrations',
            'avgRating',
            'ratingsCount'
        ));
    }

    public function export(Activity $activity)
    {
        if ($activity->supervisor_id !== Auth::id()) {
            abort(403, 'هذا النشاط ليس تحت إشرافك');
        }

        return back()->with('info', 'ميزة التصدير قيد التطوير');
    }
}