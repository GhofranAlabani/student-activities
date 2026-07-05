<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. الإحصائيات الأساسية
        $totalActivities = Activity::count();
        $totalStudents = User::where('role', 'student')->count();
        $totalRegistrations = DB::table('registrations')->count();

        // ============================================
        // 📊 بيانات الرسوم البيانية (Charts Data)
        // ============================================

        // أ) المخطط الخطي: التسجيلات حسب الشهر
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

        // ب) المخطط الدائري: توزيع الطلاب حسب نوع النشاط
        $activityTypes = DB::table('registrations')
            ->join('activities', 'registrations.activity_id', '=', 'activities.id')
            ->join('activity_types', 'activities.type_id', '=', 'activity_types.id')
            ->select('activity_types.name', DB::raw('count(registrations.id) as count'))
            ->groupBy('activity_types.name')
            ->pluck('count', 'name');

        $typeLabels = $activityTypes->keys()->toArray();
        $typeData = $activityTypes->values()->toArray();

        // ============================================
        // 🕐 بيانات آخر النشاطات (Recent Activity Feed)
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
        // 🔔 بيانات الإشعارات (Notifications)
        // ============================================
        
        // 1. الأنشطة اللي اكتمل عددها
        $fullActivities = DB::table('activities')
            ->leftJoin('registrations', 'activities.id', '=', 'registrations.activity_id')
            ->select('activities.id', 'activities.title', 'activities.max_participants', 
                     DB::raw('COUNT(registrations.id) as registered_count'))
            ->whereNotNull('activities.max_participants')
            ->groupBy('activities.id', 'activities.title', 'activities.max_participants')
            ->havingRaw('COUNT(registrations.id) >= activities.max_participants')
            ->get();

        // 2. الاستبيانات الجديدة
        $newSurveys = DB::table('survey_questions')
            ->select('survey_questions.id', 'survey_questions.question', 'survey_questions.created_at')
            ->orderBy('survey_questions.created_at', 'desc')
            ->limit(5)
            ->get();

        // 3. التسجيلات الجديدة (آخر 24 ساعة)
        $newRegistrations = DB::table('registrations')
            ->where('created_at', '>=', now()->subHours(24))
            ->count();

        // حساب عدد الإشعارات الكلي
        $notificationsCount = $fullActivities->count() + $newSurveys->count() + ($newRegistrations > 10 ? 1 : 0);

        // ============================================
        // إرجاع البيانات للـ View
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
            'fullActivities',
            'newSurveys',
            'newRegistrations',
            'notificationsCount'
        ));
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