<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class AdminDashboardController extends Controller
{
   public function index()
{
    // الإحصائيات الأساسية
    $totalActivities = Activity::count();
    $totalStudents = User::where('role', 'student')->count();
    $totalRegistrations = DB::table('registrations')->count();

    // ============================================
    // 🔔 بيانات الإشعارات - نسخة مبسطة وآمنة
    // ============================================
    
    // 1. الأنشطة المكتملة (فقط إذا كان العمود موجوداً)
    $fullActivities = collect([]);
    
    // تحقق من وجود العمود قبل الاستخدام
    if (Schema::hasColumn('activities', 'max_participants')) {
        $fullActivities = DB::table('activities')
            ->leftJoin('registrations', 'activities.id', '=', 'registrations.activity_id')
            ->select(
                'activities.id', 
                'activities.title', 
                'activities.max_participants',
                DB::raw('COUNT(registrations.id) as registered_count')
            )
            ->whereNotNull('activities.max_participants')
            ->groupBy('activities.id', 'activities.title', 'activities.max_participants')
            ->havingRaw('COUNT(registrations.id) >= activities.max_participants')
            ->limit(5)
            ->get();
    }

    // 2. الاستبيانات الجديدة (آخر 7 أيام)
    $newSurveys = DB::table('survey_questions')
        ->select('id', 'question', 'created_at')
        ->where('created_at', '>=', now()->subDays(7))
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    // 3. التسجيلات الجديدة (آخر 24 ساعة)
    $newRegistrations = DB::table('registrations')
        ->where('created_at', '>=', now()->subHours(24))
        ->count();

    // حساب العدد الكلي للإشعارات
    $notificationsCount = $fullActivities->count() + $newSurveys->count() + ($newRegistrations > 10 ? 1 : 0);

    // ============================================
    // 📊 الرسوم البيانية
    // ============================================
    $monthlyRegistrations = DB::table('registrations')
        ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('count(*) as count'))
        ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
        ->orderBy('month', 'asc')
        ->get();

    $months = [];
    $counts = [];
    foreach ($monthlyRegistrations as $item) {
        $date = Carbon::parse($item->month . '-01');
        $months[] = $date->format('M');
        $counts[] = $item->count;
    }

    $activityTypes = DB::table('registrations')
        ->join('activities', 'registrations.activity_id', '=', 'activities.id')
        ->join('activity_types', 'activities.type_id', '=', 'activity_types.id')
        ->select('activity_types.name', DB::raw('count(registrations.id) as count'))
        ->groupBy('activity_types.name')
        ->pluck('count', 'name');

    $typeLabels = $activityTypes->keys()->toArray();
    $typeData = $activityTypes->values()->toArray();

    // ============================================
    // 🕐 آخر النشاطات
    // ============================================
    $recentActivities = DB::table('registrations')
        ->join('users', 'registrations.student_id', '=', 'users.id')
        ->join('activities', 'registrations.activity_id', '=', 'activities.id')
        ->select(
            'registrations.created_at',
            'users.name as student_name',
            'activities.title as activity_title'
        )
        ->orderBy('registrations.created_at', 'desc')
        ->limit(5)
        ->get();

    // ============================================
    return view('admin.dashboard', compact(
        'totalActivities',
        'totalStudents',
        'totalRegistrations',
        'months',
        'counts',
        'typeLabels',
        'typeData',
        'recentActivities',
        'fullActivities',      // ✅ للإشعارات
        'newSurveys',          // ✅ للإشعارات
        'newRegistrations',    // ✅ للإشعارات
        'notificationsCount'   // ✅ عدد الإشعارات
    ));
}
    /**
     * عرض صفحة تعديل تسجيل
     */
    public function editRegistration($id)
    {
        $registration = DB::table('registrations')
            ->join('users', 'registrations.student_id', '=', 'users.id')
            ->join('activities', 'registrations.activity_id', '=', 'activities.id')
            ->select('registrations.*', 'users.name as student_name', 'activities.title as activity_title')
            ->where('registrations.id', $id)
            ->first();

        if (!$registration) {
            return redirect()->route('admin.all-registrations')->with('error', 'التسجيل غير موجود');
        }

        $students = User::where('role', 'student')->get();
        $activities = Activity::all();

        return view('admin.edit-registration', compact('registration', 'students', 'activities'));
    }

    /**
     * تحديث تسجيل
     */
    public function updateRegistration(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'activity_id' => 'required|exists:activities,id',
            'status' => 'required|in:مسجل,مؤكد,ملغي',
        ]);

        DB::table('registrations')
            ->where('id', $id)
            ->update([
                'student_id' => $request->student_id,
                'activity_id' => $request->activity_id,
                'status' => $request->status,
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.all-registrations')->with('success', 'تم تحديث التسجيل بنجاح');
    }

    /**
     * حذف تسجيل
     */
    public function destroyRegistration($id)
    {
        try {
            DB::table('registrations')->where('id', $id)->delete();
            return redirect()->route('admin.all-registrations')->with('success', 'تم حذف التسجيل بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('admin.all-registrations')->with('error', 'حدث خطأ أثناء الحذف');
        }
    }

    /**
     * عرض تسجيلات نشاط معين
     */
    public function showRegistrations($id)
    {
        $activity = Activity::findOrFail($id);
        $registrations = DB::table('registrations')
            ->where('activity_id', $id)
            ->join('users', 'registrations.student_id', '=', 'users.id')
            ->select('registrations.*', 'users.name', 'users.email')
            ->get();

        return view('admin.registrations', compact('activity', 'registrations'));
    }

    /**
     * عرض جميع الطلاب
     */
    public function showAllStudents()
    {
        $students = User::where('role', 'student')
            ->latest()
            ->paginate(15);

        return view('admin.students', compact('students'));
    }

    /**
     * عرض جميع التسجيلات
     */
    public function allRegistrations()
    {
        $registrations = DB::table('registrations')
            ->join('users', 'registrations.student_id', '=', 'users.id')
            ->join('activities', 'registrations.activity_id', '=', 'activities.id')
            ->select(
                'registrations.*',
                'users.name as student_name',
                'users.email as student_email',
                'activities.title as activity_title'
            )
            ->latest('registrations.created_at')
            ->paginate(15);

        return view('admin.all-registrations', compact('registrations'));
    }
}