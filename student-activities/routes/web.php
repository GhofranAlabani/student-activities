<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ActivityReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\SupervisorDashboardController;

// ========================
// 🏠 الصفحة الرئيسية
// ========================
Route::get('/', function () {
    return view('welcome');
});

// ========================
// 📊 لوحة التحكم العامة (للمستخدم العادي)
// ========================
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ========================
// 🎓 لوحة تحكم الطالب
// ========================
Route::get('/student/dashboard', function () {
    // ملاحظة: يمكنك إنشاء view خاص لاحقاً باسم 'student.dashboard'
    return view('dashboard'); 
})->middleware(['auth'])->name('student.dashboard');

// ========================
// 👨‍🏫 لوحة تحكم المشرف
// ========================
Route::get('/supervisor/dashboard', function () {
    // ملاحظة: يمكنك إنشاء view خاص لاحقاً باسم 'supervisor.dashboard'
    return view('dashboard');
})->middleware(['auth'])->name('supervisor.dashboard');

// ========================
// 📚 مسارات الأنشطة (عامة)
// ========================
Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
Route::get('/activities/{id}', [ActivityController::class, 'show'])->name('activities.show');

// ========================
// 🔐 مسارات تتطلب تسجيل دخول
// ========================
Route::middleware('auth')->group(function () {
    
    // التسجيل في نشاط
    Route::post('/activities/register/{id}', [RegistrationController::class, 'store'])
        ->name('activities.register');
    
    // إضافة/إزالة من المفضلة
    Route::post('/activities/{id}/favorite', [ActivityController::class, 'toggleFavorite'])
        ->name('activities.favorite');
    
    // إرسال تقرير عن نشاط
    Route::post('/reports', [ActivityReportController::class, 'store'])
        ->name('reports.store');

    // ========================
    // 👤 الملف الشخصي
    // ========================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ========================
// 🔐 مسارات المصادقة (تسجيل الدخول، الخروج، إلخ)
// ========================
require __DIR__.'/auth.php';