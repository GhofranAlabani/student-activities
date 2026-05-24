<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * عرض لوحة تحكم المدير
     */
    public function index()
    {
        // 1️⃣ حساب عدد الأنشطة الكلية
        $totalActivities = Activity::count();

        // 2️⃣ حساب عدد الطلاب (المستخدمين)
        $totalStudents = User::count(); 

        // 3️⃣ حساب عدد التسجيلات (مع حماية في حال عدم وجود الجدول)
        $totalRegistrations = 0;
        try {
            $totalRegistrations = DB::table('registrations')->count();
        } catch (\Exception $e) {
            // إذا كان جدول التسجيلات غير موجود، نرجع 0 بدون أخطاء
            $totalRegistrations = 0;
        }

        // ✅ إرسال البيانات للعرض (استخدمنا أسماء المتغيرات كنصوص بين علامات تنصيص)
        return view('dashboard', [
            'totalActivities' => $totalActivities,
            'totalStudents' => $totalStudents,
            'totalRegistrations' => $totalRegistrations
        ]);
    }
        /**
     * عرض الطلاب المسجلين في نشاط معين
     */
    public function showRegistrations($id)
    {
        
            if (!auth()->check() || auth()->user()->role !== 'admin') {
                 abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة.');
    
                 }
        $activity = \App\Models\Activity::with(['users', 'activityType'])->findOrFail($id);
        $students = $activity->users; // الطلاب المسجلين في هذا النشاط
        
        return view('admin.registrations', compact('activity', 'students'));
    }

    /**
     * عرض كل التسجيلات
     */
    public function allRegistrations()
    {
        $registrations = \DB::table('registrations')
            ->join('users', 'registrations.user_id', '=', 'users.id')
            ->join('activities', 'registrations.activity_id', '=', 'activities.id')
            ->select('users.name as student_name', 'users.email', 'activities.title as activity_title', 'registrations.created_at')
            ->orderBy('registrations.created_at', 'desc')
            ->get();
        
        return view('admin.all-registrations', compact('registrations'));
    }
}