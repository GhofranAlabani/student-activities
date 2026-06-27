<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ActivityReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Admin\SurveyQuestionController;
use App\Http\Controllers\Student\SurveyResponseController;

// ===== الصفحة الرئيسية =====
Route::get('/', function () {
    return view('welcome');
});

// ===== التوجيه الذكي حسب الدور =====
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if (!$user) {
        return redirect('/login');
    }
    
    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'staff':
            return redirect()->route('staff.dashboard');
        default:
            return redirect()->route('student.dashboard');
    }
})->middleware(['auth'])->name('dashboard');

// ===== لوحة تحكم الطالب =====
Route::get('/student/dashboard', function () {
    return view('student.dashboard', [
        'totalActivities' => \App\Models\Activity::count(),
        'totalStudents' => \App\Models\User::count(),
        'totalRegistrations' => 0,
    ]);
})->middleware(['auth'])->name('student.dashboard');

// ===== عرض الأنشطة العامة =====
Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
Route::get('/activities/create', [ActivityController::class, 'create'])->name('activities.create');
Route::get('/activities/{id}', [ActivityController::class, 'show'])->name('activities.show');

// ===== المسارات المحمية بالمصادقة =====
Route::middleware('auth')->group(function () {

    // ========== لوحة تحكم الأدمن ==========
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/activity/{id}/registrations', [AdminDashboardController::class, 'showRegistrations'])->name('admin.registrations');
    Route::get('/admin/students', [AdminDashboardController::class, 'showAllStudents'])->name('admin.students');
    Route::get('/admin/all-registrations', [AdminDashboardController::class, 'allRegistrations'])->name('admin.all-registrations');

    // ========== إدارة المشرفين (للأدمن فقط) ==========
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
        Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
        Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
        Route::get('/staff/{id}', [StaffController::class, 'show'])->name('staff.show');
        Route::get('/staff/{id}/edit', [StaffController::class, 'edit'])->name('staff.edit');
        Route::put('/staff/{id}', [StaffController::class, 'update'])->name('staff.update');
        Route::delete('/staff/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');
    });

    // ========== الإعلانات ==========
    Route::get('/admin/announcements', [AnnouncementController::class, 'index'])->name('admin.announcements');
    Route::get('/admin/announcements/create', [AnnouncementController::class, 'create'])->name('admin.announcements.create');
    Route::post('/admin/announcements', [AnnouncementController::class, 'store'])->name('admin.announcements.store');
    Route::get('/admin/announcements/{id}/edit', [AnnouncementController::class, 'edit'])->name('admin.announcements.edit');
    Route::put('/admin/announcements/{id}', [AnnouncementController::class, 'update'])->name('admin.announcements.update');
    Route::delete('/admin/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('admin.announcements.destroy');

    // ========== الاستبيانات ==========
    Route::get('/admin/survey-questions', [SurveyQuestionController::class, 'index'])->name('admin.survey-questions.index');
    Route::get('/admin/survey-questions/create', [SurveyQuestionController::class, 'create'])->name('admin.survey-questions.create');
    Route::post('/admin/survey-questions', [SurveyQuestionController::class, 'store'])->name('admin.survey-questions.store');
    Route::get('/admin/survey-questions/{id}/edit', [SurveyQuestionController::class, 'edit'])->name('admin.survey-questions.edit');
    Route::put('/admin/survey-questions/{id}', [SurveyQuestionController::class, 'update'])->name('admin.survey-questions.update');
    Route::delete('/admin/survey-questions/{id}', [SurveyQuestionController::class, 'destroy'])->name('admin.survey-questions.destroy');
    Route::post('/admin/survey-questions/reset', [SurveyQuestionController::class, 'resetToDefault'])->name('admin.survey-questions.reset');

    // إحصائيات الاستبيان
    Route::get('/admin/survey-stats', [App\Http\Controllers\Admin\SurveyStatsController::class, 'index'])->name('admin.survey-stats.index');
    Route::get('/admin/survey-stats/activity/{activityId}', [App\Http\Controllers\Admin\SurveyStatsController::class, 'activityStats'])->name('admin.survey-stats.activity');
    Route::get('/admin/survey-stats/export-pdf', [App\Http\Controllers\Admin\SurveyStatsController::class, 'exportPDF'])->name('admin.survey-stats.export.pdf');
    Route::get('/admin/survey-stats/export-excel', [App\Http\Controllers\Admin\SurveyStatsController::class, 'exportExcel'])->name('admin.survey-stats.export.excel');

    // استبيانات الطالب
    Route::get('/activities/{activity}/survey', [SurveyResponseController::class, 'show'])->name('student.survey.show');
    Route::post('/activities/{activity}/survey', [SurveyResponseController::class, 'submit'])->name('student.survey.submit');

    // ========== الأنشطة ==========
    Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');
    Route::get('/activities/{id}/edit', [ActivityController::class, 'edit'])->name('activities.edit');
    Route::put('/activities/{id}', [ActivityController::class, 'update'])->name('activities.update');
    Route::delete('/activities/{id}', [ActivityController::class, 'destroy'])->name('activities.destroy');
    Route::post('/activities/register/{id}', [RegistrationController::class, 'store'])->name('activities.register');
    Route::delete('/activities/unregister/{id}', function ($id) {
        auth()->user()->activities()->detach($id);
        return back()->with('success', 'تم إلغاء التسجيل بنجاح');
    })->name('activities.unregister');
    Route::post('/activities/{id}/favorite', [ActivityController::class, 'toggleFavorite'])->name('activities.favorite');

    // ========== صفحات الطالب ==========
    Route::get('/student/my-activities', function () {
        $activities = auth()->user()->activities()->paginate(9);
        return view('student.my-activities', compact('activities'));
    })->name('student.my-activities');

    Route::get('/student/favorites', function () {
        $favorites = auth()->user()->favorites()->get();
        return view('student.favorites', compact('favorites'));
    })->name('student.favorites');

    Route::get('/student/profile', function () {
        $user = auth()->user();
        $activities = $user->activities()->latest()->get();
        $favorites = $user->favorites()->count();
        return view('student.profile', compact('user', 'activities', 'favorites'));
    })->name('student.profile');

    // ========== التقارير ==========
    Route::post('/reports', [ActivityReportController::class, 'store'])->name('reports.store');

    // ========== الملف الشخصي ==========
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ========== إدارة المستخدمين ==========
    Route::patch('/admin/users/{id}/role', function (\Illuminate\Http\Request $request, $id) {
        $user = \App\Models\User::findOrFail($id);
        $user->update(['role' => $request->role]);
        return back()->with('success', 'تم تغيير الصلاحية بنجاح');
    })->name('admin.user.role');

    // ========== التقييمات ==========
    Route::post('/activities/{id}/rate', function (\Illuminate\Http\Request $request, $id) {
        $request->validate(['rating' => 'required|integer|min:1|max:5']);
        \App\Models\Rating::updateOrCreate(
            ['user_id' => auth()->id(), 'activity_id' => $id],
            ['rating' => $request->rating, 'review' => $request->review]
        );
        return back()->with('success', 'تم إضافة التقييم بنجاح!');
    })->name('activities.rate');

    // ========== الإشعارات ==========
    Route::get('/notifications/mark-read/{id}', function ($id) {
        \App\Models\Notification::where('id', $id)->where('user_id', auth()->id())->update(['is_read' => true, 'read_at' => now()]);
        return back();
    })->name('notifications.read');

    Route::get('/notifications', function () {
        $notifications = \App\Models\Notification::where('user_id', auth()->id())->latest()->paginate(20);
        return view('notifications.index', compact('notifications'));
    })->name('notifications.index');

    Route::post('/notifications/mark-all-read', function () {
        \App\Models\Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
        return back()->with('success', 'تم تعليم جميع الإشعارات كمقروءة');
    })->name('notifications.mark-all-read');

    Route::delete('/notifications/delete/{id}', function ($id) {
        \App\Models\Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();
        return back()->with('success', 'تم حذف الإشعار بنجاح');
    })->name('notifications.delete');

}); // نهاية مجموعة المصادقة العامة

// ========== 🆕 مسارات المشرف (Staff) ==========
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    // Dashboard المشرف
    Route::get('/dashboard', [App\Http\Controllers\Staff\StaffDashboardController::class, 'index'])->name('dashboard');
    
    // مسار اختبار مباشر
    Route::get('/test', function () {
        return "✅ أنت الآن في واجهة المشرف! المستخدم: " . auth()->user()->name . " | الدور: " . auth()->user()->role;
    })->name('test');
    
    // إدارة أنشطته فقط
    Route::get('/activities', [App\Http\Controllers\Staff\StaffActivityController::class, 'index'])->name('activities.index');
    Route::get('/activities/create', [App\Http\Controllers\Staff\StaffActivityController::class, 'create'])->name('activities.create');
    Route::post('/activities', [App\Http\Controllers\Staff\StaffActivityController::class, 'store'])->name('activities.store');
    Route::get('/activities/{activity}', [App\Http\Controllers\Staff\StaffActivityController::class, 'show'])->name('activities.show');
    Route::get('/activities/{activity}/edit', [App\Http\Controllers\Staff\StaffActivityController::class, 'edit'])->name('activities.edit');
    Route::put('/activities/{activity}', [App\Http\Controllers\Staff\StaffActivityController::class, 'update'])->name('activities.update');
    Route::delete('/activities/{activity}', [App\Http\Controllers\Staff\StaffActivityController::class, 'destroy'])->name('activities.destroy');
    
    // إدارة التسجيلات
    Route::get('/activities/{activity}/registrations', [App\Http\Controllers\Staff\StaffRegistrationController::class, 'index'])->name('registrations.index');
    Route::post('/registrations/{registration}/approve', [App\Http\Controllers\Staff\StaffRegistrationController::class, 'approve'])->name('registrations.approve');
    Route::post('/registrations/{registration}/reject', [App\Http\Controllers\Staff\StaffRegistrationController::class, 'reject'])->name('registrations.reject');
    
    // التقارير
    Route::get('/activities/{activity}/report', [App\Http\Controllers\Staff\StaffReportController::class, 'show'])->name('report.show');
    Route::get('/activities/{activity}/report/export', [App\Http\Controllers\Staff\StaffReportController::class, 'export'])->name('report.export');
});

require __DIR__.'/auth.php';