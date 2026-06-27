<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Registration;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // أنشطة المشرف فقط
        $myActivities = Activity::where('supervisor_id', $user->id)
            ->with(['activityType'])
            ->withCount('registrations')
            ->latest()
            ->get();
        
        // إحصائيات
        $myActivitiesCount = $myActivities->count();
        $totalParticipants = $myActivities->sum('registrations_count');
        
        // ✅ التسجيلات في أنشطته (بدون status)
        $pendingRegistrations = Registration::whereHas('activity', function($q) use ($user) {
                $q->where('supervisor_id', $user->id);
            })
            ->with(['user', 'activity'])
            ->latest()
            ->take(10)
            ->get();
        
        // متوسط التقييم لأنشطته
        $activityIds = $myActivities->pluck('id');
        $avgRating = Rating::whereIn('activity_id', $activityIds)->avg('rating') ?? 0;
        
        return view('staff.dashboard', compact(
            'myActivities',
            'myActivitiesCount',
            'totalParticipants',
            'pendingRegistrations',
            'avgRating'
        ));
    }
}