<?php

namespace App\Http\Controllers\Staff;

use App\Exports\StaffReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Registration;
use App\Models\ActivityType;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StaffReportController extends Controller
{
    /**
     * عرض صفحة التقارير العامة
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // فلاتر التاريخ
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        
        // استعلام أساسي للأنشطة
        $activitiesQuery = Activity::where('supervisor_id', $user->id);
        
        if ($fromDate) {
            $activitiesQuery->where('date', '>=', $fromDate);
        }
        if ($toDate) {
            $activitiesQuery->where('date', '<=', $toDate);
        }
        
        $activities = $activitiesQuery->get();
        
        // === الإحصائيات العامة ===
        $totalActivities = $activities->count();
        $totalRegistrations = Registration::whereIn('activity_id', $activities->pluck('id'))->count();
       // التحقق من وجود عمود status
$hasStatusColumn = \Schema::hasColumn('registrations', 'status');

if ($hasStatusColumn) {
    $totalApproved = Registration::whereIn('activity_id', $activities->pluck('id'))
        ->where('status', 'approved')->count();
    $totalPending = Registration::whereIn('activity_id', $activities->pluck('id'))
        ->where('status', 'pending')->count();
    $totalRejected = Registration::whereIn('activity_id', $activities->pluck('id'))
        ->where('status', 'rejected')->count();
} else {
    $totalApproved = 0;
    $totalPending = 0;
    $totalRejected = 0;
}
        
        // متوسط التقييمات
        $avgRating = Rating::whereIn('activity_id', $activities->pluck('id'))
            ->avg('rating') ?? 0;
        
        // إجمالي النقاط الموزعة
        $totalPoints = $activities->sum('points');
        
        // === بيانات الرسوم البيانية ===
        
        // 1. الأنشطة حسب النوع
        $activitiesByType = ActivityType::select('activity_types.name', DB::raw('count(activities.id) as count'))
            ->leftJoin('activities', 'activity_types.id', '=', 'activities.type_id')
            ->where('activities.supervisor_id', $user->id)
            ->groupBy('activity_types.id', 'activity_types.name')
            ->get();
        
        // 2. التسجيلات الشهرية (آخر 6 أشهر)
        $monthlyRegistrations = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = Registration::whereIn('activity_id', $activities->pluck('id'))
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
            $monthlyRegistrations[] = [
                'month' => $month->format('M Y'),
                'count' => $count
            ];
        }
        
        // 3. حالة التسجيلات
        $registrationStatus = [
            'approved' => $totalApproved,
            'pending' => $totalPending,
            'rejected' => $totalRejected,
        ];
        
        // === أفضل 5 أنشطة ===
        $topActivities = $activities->map(function($activity) {
            return [
                'title' => $activity->title,
                'registrations' => $activity->registrations()->count(),
                'date' => $activity->date->format('Y/m/d'),
            ];
        })->sortByDesc('registrations')->take(5)->values();
        
        return view('staff.reports.index', compact(
            'totalActivities',
            'totalRegistrations',
            'totalApproved',
            'totalPending',
            'totalRejected',
            'avgRating',
            'totalPoints',
            'activitiesByType',
            'monthlyRegistrations',
            'registrationStatus',
            'topActivities',
            'fromDate',
            'toDate'
        ));
    }
    
 /**
 * تصدير التقرير كـ PDF
 */
public function exportPDF(Request $request)
{
    $user = Auth::user();
    
    // فلاتر التاريخ
    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');
    
    // استعلام أساسي للأنشطة
    $activitiesQuery = Activity::where('supervisor_id', $user->id);
    
    if ($fromDate) {
        $activitiesQuery->where('date', '>=', $fromDate);
    }
    if ($toDate) {
        $activitiesQuery->where('date', '<=', $toDate);
    }
    
    $activities = $activitiesQuery->with(['activityType', 'registrations'])->get();
    
    // الإحصائيات
    $totalActivities = $activities->count();
    $totalRegistrations = Registration::whereIn('activity_id', $activities->pluck('id'))->count();
    
    // التحقق من وجود عمود status
    $hasStatusColumn = \Schema::hasColumn('registrations', 'status');
    
    if ($hasStatusColumn) {
        $totalApproved = Registration::whereIn('activity_id', $activities->pluck('id'))
            ->where('status', 'approved')->count();
        $totalPending = Registration::whereIn('activity_id', $activities->pluck('id'))
            ->where('status', 'pending')->count();
    } else {
        $totalApproved = $totalRegistrations; // كلهم مقبولين
        $totalPending = 0;
    }
    
    $avgRating = Rating::whereIn('activity_id', $activities->pluck('id'))
        ->avg('rating') ?? 0;
    
    $pdf = Pdf::loadView('staff.reports.pdf', compact(
        'activities',
        'totalActivities',
        'totalRegistrations',
        'totalApproved',
        'totalPending',
        'avgRating',
        'fromDate',
        'toDate',
        'user'
    ));
    
    return $pdf->download('تقرير-الأنشطة-' . now()->format('Y-m-d') . '.pdf');
}
   /**
 * تصدير التقرير كـ Excel
 */
public function exportExcel(Request $request)
{
    return Excel::download(new StaffReportExport(
        $request->input('from_date'),
        $request->input('to_date')
    ), 'تقرير-الأنشطة-' . now()->format('Y-m-d') . '.xlsx');
}
    /**
     * عرض تقرير نشاط محدد
     */
    public function show(Activity $activity)
    {
        if ($activity->supervisor_id !== Auth::id()) {
            abort(403, 'هذا النشاط ليس تحت إشرافك');
        }
        
        $activity->load(['activityType', 'registrations.user', 'ratings']);
        
        $totalRegistrations = $activity->registrations->count();
        $approvedRegistrations = $activity->registrations->where('status', 'approved')->count();
        $pendingRegistrations = $activity->registrations->where('status', 'pending')->count();
        $avgRating = $activity->ratings->avg('rating') ?? 0;
        $ratingsCount = $activity->ratings->count();
        
        return view('staff.reports.show', compact(
            'activity',
            'totalRegistrations',
            'approvedRegistrations',
            'pendingRegistrations',
            'avgRating',
            'ratingsCount'
        ));
    }
    
    /**
     * تصدير تقرير نشاط محدد
     */
    public function export(Activity $activity)
    {
        if ($activity->supervisor_id !== Auth::id()) {
            abort(403, 'هذا النشاط ليس تحت إشرافك');
        }
        
        return response()->json(['message' => 'تصدير التقرير قيد التطوير']);
    }
}