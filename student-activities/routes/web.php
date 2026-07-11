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
    $user = auth()->user();
    
    $registeredActivities = \App\Models\Activity::join('registrations', 'activities.id', '=', 'registrations.activity_id')
        ->where('registrations.student_id', $user->id)
        ->where('activities.date', '>=', now())
        ->orderBy('activities.date', 'asc')
        ->select('activities.*')
        ->limit(5)
        ->get();
    
    $totalRegistered = \DB::table('registrations')
        ->where('student_id', $user->id)
        ->count();
    
    $totalPoints = $user->total_points ?? 0;
    $favoritesCount = $user->favorites()->count();
    
    return view('student.dashboard', [
        'registeredActivities' => $registeredActivities,
        'totalActivities' => \App\Models\Activity::count(),
        'totalStudents' => \App\Models\User::count(),
        'totalRegistered' => $totalRegistered,
        'totalPoints' => $totalPoints,
        'favoritesCount' => $favoritesCount,
        'announcements' => \App\Models\Announcement::with('creator')
            ->active()
            ->latest()
            ->take(5)
            ->get(),
    ]);
})->middleware(['auth'])->name('student.dashboard');

// ===== عرض الأنشطة العامة =====
Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
Route::get('/activities/create', [ActivityController::class, 'create'])->name('activities.create');
Route::get('/activities/{id}', [ActivityController::class, 'show'])->name('activities.show');

// ===== المسارات المحمية بالمصادقة =====
Route::middleware('auth')->group(function () {

    // ========== الملف الشخصي (لجميع المستخدمين) ==========
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // ========== لوحة تحكم الأدمن ==========
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // الطلاب
        Route::get('/students', [AdminDashboardController::class, 'showAllStudents'])->name('students');
        
        // المشرفين
        Route::get('/staff', [AdminDashboardController::class, 'showStaff'])->name('staff');
        Route::delete('/staff/{id}', [AdminDashboardController::class, 'destroyStaff'])->name('staff.destroy');
        
        // التسجيلات
        Route::get('/all-registrations', [AdminDashboardController::class, 'allRegistrations'])->name('all-registrations');
        Route::get('/activity/{id}/registrations', [AdminDashboardController::class, 'showRegistrations'])->name('activity.registrations');
        Route::get('/registrations/{id}/edit', [AdminDashboardController::class, 'editRegistration'])->name('registrations.edit');
        Route::put('/registrations/{id}', [AdminDashboardController::class, 'updateRegistration'])->name('registrations.update');
        Route::delete('/registrations/{id}', [AdminDashboardController::class, 'destroyRegistration'])->name('registrations.destroy');
        
        // إدارة المشرفين (CRUD كامل)
        Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
        Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
        Route::get('/staff/{id}', [StaffController::class, 'show'])->name('staff.show');
        Route::get('/staff/{id}/edit', [StaffController::class, 'edit'])->name('staff.edit');
        Route::put('/staff/{id}', [StaffController::class, 'update'])->name('staff.update');
        
        // تغيير صلاحية المستخدم
        Route::patch('/users/{id}/role', [AdminDashboardController::class, 'updateRole'])->name('users.updateRole');
        
        // ✅ الإعلانات (للأدمن فقط)
        Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements');
        Route::get('/announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create');
        Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::get('/announcements/{id}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
        Route::put('/announcements/{id}', [AnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

        // الاستبيانات
        Route::get('/survey-questions', [SurveyQuestionController::class, 'index'])->name('survey-questions.index');
        Route::get('/survey-questions/create', [SurveyQuestionController::class, 'create'])->name('survey-questions.create');
        Route::post('/survey-questions', [SurveyQuestionController::class, 'store'])->name('survey-questions.store');
        Route::get('/survey-questions/{id}/edit', [SurveyQuestionController::class, 'edit'])->name('survey-questions.edit');
        Route::put('/survey-questions/{id}', [SurveyQuestionController::class, 'update'])->name('survey-questions.update');
        Route::delete('/survey-questions/{id}', [SurveyQuestionController::class, 'destroy'])->name('survey-questions.destroy');
        Route::post('/survey-questions/reset', [SurveyQuestionController::class, 'resetToDefault'])->name('survey-questions.reset');

        // إجابات الاستبيانات
        Route::get('/survey-responses', [App\Http\Controllers\Admin\SurveyResponseController::class, 'index'])->name('survey-responses.index');
        Route::get('/survey-responses/{id}', [App\Http\Controllers\Admin\SurveyResponseController::class, 'show'])->name('survey-responses.show');

        // إحصائيات الاستبيان
        Route::get('/survey-stats', [App\Http\Controllers\Admin\SurveyStatsController::class, 'index'])->name('survey-stats.index');
        Route::get('/survey-stats/activity/{activityId}', [App\Http\Controllers\Admin\SurveyStatsController::class, 'activityStats'])->name('survey-stats.activity');
        Route::get('/survey-stats/export-pdf', [App\Http\Controllers\Admin\SurveyStatsController::class, 'exportPDF'])->name('survey-stats.export.pdf');
        Route::get('/survey-stats/export-excel', [App\Http\Controllers\Admin\SurveyStatsController::class, 'exportExcel'])->name('survey-stats.export.excel');
    });

    // ========== الأنشطة (لجميع المستخدمين) ==========
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
    
    // تصدير النشاط إلى Google Calendar
    Route::get('/activities/{activity}/export-calendar', [App\Http\Controllers\ActivityController::class, 'exportToCalendar'])
        ->name('activities.export-calendar');

    // تصفح الأنشطة للطالب
    Route::get('/student/activities', [ActivityController::class, 'index'])
        ->name('student.activities');
    
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
        $badges = \App\Models\Badge::where('is_active', true)->orderBy('requirement')->get();
        $userBadges = $user->badges()->pluck('badges.id')->toArray();
        return view('student.profile', compact('user', 'activities', 'favorites', 'badges', 'userBadges'));
    })->name('student.profile');

    // ========== الحضور (Attendance) ==========
    Route::post('/attendance/check-in-qr', [App\Http\Controllers\Student\AttendanceController::class, 'checkInWithQR'])->name('attendance.check-in-qr');
    Route::post('/activities/{activity}/attendance', [App\Http\Controllers\Student\AttendanceController::class, 'manualCheckIn'])->name('attendance.check-in');
    Route::get('/attendance', [App\Http\Controllers\Student\AttendanceController::class, 'myAttendance'])->name('attendance.index');
    Route::get('/attendance/scan', [App\Http\Controllers\Student\AttendanceController::class, 'scanPage'])->name('attendance.scan');
    
    // ========== التقارير ==========
    Route::post('/reports', [ActivityReportController::class, 'store'])->name('reports.store');

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
    Route::middleware('auth')->group(function () {
        
        Route::post('/admin/notifications/decrement', function () {
            return response()->json(['success' => true]);
        })->name('admin.notifications.decrement');

        Route::get('/notifications', function () {
            $user = auth()->user();
            $notifications = \App\Models\Notification::where('user_id', $user->id)->latest()->paginate(20);
            $unreadCount = \App\Models\Notification::where('user_id', $user->id)->where('is_read', false)->count();
            return view('notifications.index', compact('notifications', 'unreadCount'));
        })->name('notifications.index');

        Route::get('/notifications/mark-read/{id}', function ($id) {
            $notification = \App\Models\Notification::where('id', $id)->where('user_id', auth()->id())->first();
            if ($notification) {
                $notification->update(['is_read' => true, 'read_at' => now()]);
                if ($notification->action_url) {
                    return redirect($notification->action_url);
                }
            }
            return back();
        })->name('notifications.read');

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
        
        Route::get('/notifications/latest', function () {
            $user = auth()->user();
            $notifications = \App\Models\Notification::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($n) {
                    return [
                        'id' => $n->id, 'type' => $n->type, 'title' => $n->title,
                        'message' => $n->message, 'icon' => $n->icon, 'color' => $n->color,
                        'action_url' => $n->action_url, 'is_read' => $n->is_read,
                        'created_at' => $n->created_at->diffForHumans(),
                    ];
                });
            $unreadCount = \App\Models\Notification::where('user_id', $user->id)->where('is_read', false)->count();
            return response()->json(['notifications' => $notifications, 'unread_count' => $unreadCount]);
        })->name('notifications.latest');

        Route::post('/notifications/{id}/read', function ($id) {
            $notification = \App\Models\Notification::where('id', $id)->where('user_id', auth()->id())->first();
            if ($notification) {
                $notification->update(['is_read' => true, 'read_at' => now()]);
                return response()->json(['success' => true, 'action_url' => $notification->action_url]);
            }
            return response()->json(['success' => false], 404);
        })->name('notifications.ajax-read');

    });

}); // نهاية مجموعة المصادقة العامة

// ========== 🆕 مسارات المشرف (Staff) ==========
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Staff\StaffDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/test', function () {
        return "✅ أنت الآن في واجهة المشرف! المستخدم: " . auth()->user()->name . " | الدور: " . auth()->user()->role;
    })->name('test');
    
    // ==================== الأنشطة ====================
    Route::get('/activities', [App\Http\Controllers\Staff\StaffActivityController::class, 'index'])->name('activities.index');
    Route::get('/activities/create', [App\Http\Controllers\Staff\StaffActivityController::class, 'create'])->name('activities.create');
    Route::post('/activities', [App\Http\Controllers\Staff\StaffActivityController::class, 'store'])->name('activities.store');
    Route::get('/activities/{activity}', [App\Http\Controllers\Staff\StaffActivityController::class, 'show'])->name('activities.show');
    Route::get('/activities/{activity}/edit', [App\Http\Controllers\Staff\StaffActivityController::class, 'edit'])->name('activities.edit');
    Route::put('/activities/{activity}', [App\Http\Controllers\Staff\StaffActivityController::class, 'update'])->name('activities.update');
    Route::delete('/activities/{activity}', [App\Http\Controllers\Staff\StaffActivityController::class, 'destroy'])->name('activities.destroy');
    
    // الطلاب المسجلين في نشاط
    Route::get('/activities/{activity}/students', [App\Http\Controllers\Staff\StaffActivityController::class, 'showStudents'])->name('activity.students');
    
    // ==================== التسجيلات ====================
    Route::get('/activities/{activity}/registrations', [App\Http\Controllers\Staff\StaffRegistrationController::class, 'index'])->name('registrations.index');
    Route::post('/registrations/{registration}/approve', [App\Http\Controllers\Staff\StaffRegistrationController::class, 'approve'])->name('registrations.approve');
    Route::post('/registrations/{registration}/reject', [App\Http\Controllers\Staff\StaffRegistrationController::class, 'reject'])->name('registrations.reject');
    
    // ==================== الطلاب ====================
    Route::get('/students', [App\Http\Controllers\Staff\StaffStudentController::class, 'index'])->name('students.index');
    Route::get('/students/{student}/edit', [App\Http\Controllers\Staff\StaffStudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [App\Http\Controllers\Staff\StaffStudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [App\Http\Controllers\Staff\StaffStudentController::class, 'destroy'])->name('students.destroy');
    
    // ==================== التقارير ====================
    Route::get('/reports', [App\Http\Controllers\Staff\StaffReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-pdf', [App\Http\Controllers\Staff\StaffReportController::class, 'exportPDF'])->name('reports.export.pdf');
    Route::get('/reports/export-excel', [App\Http\Controllers\Staff\StaffReportController::class, 'exportExcel'])->name('reports.export.excel');
    Route::get('/activities/{activity}/report', [App\Http\Controllers\Staff\StaffReportController::class, 'show'])->name('report.show');
    Route::get('/activities/{activity}/report/export', [App\Http\Controllers\Staff\StaffReportController::class, 'export'])->name('report.export');
    
    // ==================== الإعلانات ====================
    Route::get('/announcements', [App\Http\Controllers\Staff\StaffAnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements', [App\Http\Controllers\Staff\StaffAnnouncementController::class, 'store'])->name('announcements.store');
    Route::delete('/announcements/{announcement}', [App\Http\Controllers\Staff\StaffAnnouncementController::class, 'destroy'])->name('announcements.destroy');
    
    // ==================== الإعدادات ====================
    Route::get('/settings', [App\Http\Controllers\Staff\StaffSettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile', [App\Http\Controllers\Staff\StaffSettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::put('/settings/password', [App\Http\Controllers\Staff\StaffSettingsController::class, 'updatePassword'])->name('settings.password.update');
    
    // ==================== الحضور ====================
    Route::get('/activities/{activity}/attendance', [App\Http\Controllers\Staff\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/activities/{activity}/attendance/qr', [App\Http\Controllers\Staff\AttendanceController::class, 'showQR'])->name('attendance.qr');
    Route::post('/activities/{activity}/attendance/manual', [App\Http\Controllers\Staff\AttendanceController::class, 'manualCheckIn'])->name('attendance.manual');
    Route::get('/activities/{activity}/attendance/export', [App\Http\Controllers\Staff\AttendanceController::class, 'exportReport'])->name('attendance.export');
    Route::post('/attendance/check-in-qr', [App\Http\Controllers\Staff\AttendanceController::class, 'checkInQR'])->name('attendance.check-in-qr');
    
    // ==================== الاستبيانات ====================
    Route::get('/survey-results/{activity}', [App\Http\Controllers\Staff\SurveyResultsController::class, 'index'])->name('survey-results');
    Route::get('/survey-results/{activity}/export', [App\Http\Controllers\Staff\SurveyResultsController::class, 'export'])->name('survey-export');
});

require __DIR__.'/auth.php';