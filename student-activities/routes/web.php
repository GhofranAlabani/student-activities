<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ActivityReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminDashboardController;

// ========================
// 🏠 الصفحة الرئيسية
// ========================
Route::get('/', function () {
    return view('welcome');
});

// ========================
// 📊 لوحات التحكم (حسب الصلاحية)
// ========================

// لوحة تحكم المدير
Route::get('/dashboard', function () {
    // التحقق من أن المستخدم أدمن
    if (!auth()->check() || auth()->user()->role !== 'admin') {
        // إذا مو أدمن، وجهه للوحة الطالب
        return redirect('/student/dashboard');
    }
    
    $totalActivities = \App\Models\Activity::count();
    $totalStudents = \App\Models\User::count();
    $totalRegistrations = \Illuminate\Support\Facades\DB::table('registrations')->count() ?? 0;
    
    return view('dashboard', compact('totalActivities', 'totalStudents', 'totalRegistrations'));
})->middleware(['auth', 'verified'])->name('dashboard');

// لوحة تحكم الطالب
Route::get('/student/dashboard', function () {
    return view('student.dashboard', [
        'totalActivities' => \App\Models\Activity::count(),
        'totalStudents' => \App\Models\User::count(),
        'totalRegistrations' => 0
    ]);
})->middleware(['auth'])->name('student.dashboard');

// لوحة تحكم المشرف
Route::get('/supervisor/dashboard', function () {
    return view('dashboard', [
        'totalActivities' => \App\Models\Activity::count(),
        'totalStudents' => \App\Models\User::count(),
        'totalRegistrations' => 0
    ]);
})->middleware(['auth'])->name('supervisor.dashboard');

// ========================
// 👑 مسارات المدير (Admin)
// ========================

// لوحة تحكم المدير (مسار بديل)
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('admin.dashboard');

// عرض الطلاب المسجلين في نشاط معين (للأدمن فقط)
Route::get('/admin/activity/{id}/registrations', [AdminDashboardController::class, 'showRegistrations'])
    ->middleware(['auth'])
    ->name('admin.registrations');

// عرض كل الطلاب (للأدمن فقط)
Route::get('/admin/students', [AdminDashboardController::class, 'showAllStudents'])
    ->middleware(['auth'])
    ->name('admin.students');

// عرض كل التسجيلات (للأدمن فقط)
Route::get('/admin/all-registrations', [AdminDashboardController::class, 'allRegistrations'])
    ->middleware(['auth'])
    ->name('admin.all-registrations');

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
// 🔐 مسارات المصادقة
// ========================
require __DIR__.'/auth.php';
Route::middleware('auth')->group(function () {
    Route::get('/student/my-activities', function () {
        $activities = auth()->user()->activities()->paginate(9);
        return view('student.my-activities', compact('activities'));
    })->name('student.my-activities');

    Route::get('/student/favorites', function () {
        $favorites = auth()->user()->favorites()->get();
        return view('student.favorites', compact('favorites'));
    })->name('student.favorites');

    Route::delete('/activities/unregister/{id}', function ($id) {
        auth()->user()->activities()->detach($id);
        return back()->with('success', '?? ???????');
    })->name('activities.unregister');
});

Route::middleware('auth')->group(function () {
    Route::get('/activities/create', [App\Http\Controllers\ActivityController::class, 'create'])->name('activities.create');
    Route::post('/activities', [App\Http\Controllers\ActivityController::class, 'store'])->name('activities.store');
    Route::get('/activities/{id}/edit', [App\Http\Controllers\ActivityController::class, 'edit'])->name('activities.edit');
    Route::put('/activities/{id}', [App\Http\Controllers\ActivityController::class, 'update'])->name('activities.update');
    Route::delete('/activities/{id}', [App\Http\Controllers\ActivityController::class, 'destroy'])->name('activities.destroy');
});
