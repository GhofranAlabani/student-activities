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
}