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

// ?????? ????????
Route::get('/', function () {
    return view('welcome');
});

// ???? ???? ??????
Route::get('/dashboard', function () {
    if (!auth()->check() || auth()->user()->role !== 'admin') {
        return redirect('/student/dashboard');
    }
    $totalActivities = \App\Models\Activity::count();
    $totalStudents = \App\Models\User::count();
    $totalRegistrations = \Illuminate\Support\Facades\DB::table('registrations')->count();
    return view('admin.dashboard', compact('totalActivities', 'totalStudents', 'totalRegistrations'));
})->middleware(['auth', 'verified'])->name('dashboard');

// ???? ???? ?????? - ?? ?????????
Route::get('/student/dashboard', function () {
    return view('student.dashboard', [
        'totalActivities' => \App\Models\Activity::count(),
        'totalStudents' => \App\Models\User::count(),
        'totalRegistrations' => 0,
    ]);
})->middleware(['auth'])->name('student.dashboard');

// ?????? ??????? ??????
Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
Route::get('/activities/create', [ActivityController::class, 'create'])->name('activities.create');
Route::get('/activities/{id}', [ActivityController::class, 'show'])->name('activities.show');

// ?????? ????? ????? ????
Route::middleware('auth')->group(function () {

    // ?????? ???? ???? ??????
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/activity/{id}/registrations', [AdminDashboardController::class, 'showRegistrations'])->name('admin.registrations');
    Route::get('/admin/students', [AdminDashboardController::class, 'showAllStudents'])->name('admin.students');
    Route::get('/admin/all-registrations', [AdminDashboardController::class, 'allRegistrations'])->name('admin.all-registrations');

    // ? ?????? ????? ??????? ?????????
    Route::get('/admin/staff', [StaffController::class, 'index'])->name('admin.staff');
    Route::get('/admin/staff/create', [StaffController::class, 'create'])->name('admin.staff.create');
    Route::post('/admin/staff', [StaffController::class, 'store'])->name('admin.staff.store');
    Route::get('/admin/staff/{id}', [StaffController::class, 'show'])->name('admin.staff.show');
    Route::get('/admin/staff/{id}/edit', [StaffController::class, 'edit'])->name('admin.staff.edit');
    Route::put('/admin/staff/{id}', [StaffController::class, 'update'])->name('admin.staff.update');
    Route::delete('/admin/staff/{id}', [StaffController::class, 'destroy'])->name('admin.staff.destroy');

    // ? ?????? ????????? ?????????? (?????)
    Route::get('/admin/announcements', [AnnouncementController::class, 'index'])->name('admin.announcements');
    Route::get('/admin/announcements/create', [AnnouncementController::class, 'create'])->name('admin.announcements.create');
    Route::post('/admin/announcements', [AnnouncementController::class, 'store'])->name('admin.announcements.store');
    Route::get('/admin/announcements/{id}/edit', [AnnouncementController::class, 'edit'])->name('admin.announcements.edit');
    Route::put('/admin/announcements/{id}', [AnnouncementController::class, 'update'])->name('admin.announcements.update');
    Route::delete('/admin/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('admin.announcements.destroy');

    // ? ?????? ????? ????? ????????? (??????) - ????? ?? ???????
    Route::get('/admin/survey-questions', [SurveyQuestionController::class, 'index'])
        ->name('admin.survey-questions.index');
    Route::get('/admin/survey-questions/create', [SurveyQuestionController::class, 'create'])
        ->name('admin.survey-questions.create');
    Route::post('/admin/survey-questions', [SurveyQuestionController::class, 'store'])
        ->name('admin.survey-questions.store');
    Route::get('/admin/survey-questions/{id}/edit', [SurveyQuestionController::class, 'edit'])
        ->name('admin.survey-questions.edit');
    Route::put('/admin/survey-questions/{id}', [SurveyQuestionController::class, 'update'])
        ->name('admin.survey-questions.update');
    Route::delete('/admin/survey-questions/{id}', [SurveyQuestionController::class, 'destroy'])
        ->name('admin.survey-questions.destroy');
    Route::post('/admin/survey-questions/reset', [SurveyQuestionController::class, 'resetToDefault'])
        ->name('admin.survey-questions.reset');

    // ? ?????? ???????? ??? ????????? (??????)
    Route::get('/activities/{activity}/survey', [SurveyResponseController::class, 'show'])
        ->name('student.survey.show');
    Route::post('/activities/{activity}/survey', [SurveyResponseController::class, 'submit'])
        ->name('student.survey.submit');

    // ???????
    Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');
    Route::get('/activities/{id}/edit', [ActivityController::class, 'edit'])->name('activities.edit');
    Route::put('/activities/{id}', [ActivityController::class, 'update'])->name('activities.update');
    Route::delete('/activities/{id}', [ActivityController::class, 'destroy'])->name('activities.destroy');
    Route::post('/activities/register/{id}', [RegistrationController::class, 'store'])->name('activities.register');
    Route::delete('/activities/unregister/{id}', function ($id) {
        auth()->user()->activities()->detach($id);
        return back()->with('success', '?? ????? ??????? ?????');
    })->name('activities.unregister');
    Route::post('/activities/{id}/favorite', [ActivityController::class, 'toggleFavorite'])->name('activities.favorite');

    // ?????? ??????
    Route::get('/student/my-activities', function () {
        $activities = auth()->user()->activities()->paginate(9);
        return view('student.my-activities', compact('activities'));
    })->name('student.my-activities');

    Route::get('/student/favorites', function () {
        $favorites = auth()->user()->favorites()->get();
        return view('student.favorites', compact('favorites'));
    })->name('student.favorites');

    // ????????
    Route::post('/reports', [ActivityReportController::class, 'store'])->name('reports.store');

    // ????? ??????
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ??????? ??????
    Route::patch('/admin/users/{id}/role', function (\Illuminate\Http\Request $request, $id) {
        $user = \App\Models\User::findOrFail($id);
        $user->update(['role' => $request->role]);
        return back()->with('success', '?? ????? ???????? ?????');
    })->name('admin.user.role');

    // ??? ?????? ??????
    Route::get('/student/profile', function () {
        $user = auth()->user();
        $activities = $user->activities()->latest()->get();
        $favorites = $user->favorites()->count();
        return view('student.profile', compact('user', 'activities', 'favorites'));
    })->name('student.profile');

    // ???????
    Route::post('/activities/{id}/rate', function (\Illuminate\Http\Request $request, $id) {
        $request->validate(['rating' => 'required|integer|min:1|max:5']);
        \App\Models\Rating::updateOrCreate(
            ['user_id' => auth()->id(), 'activity_id' => $id],
            ['rating' => $request->rating, 'review' => $request->review]
        );
        return back()->with('success', '?? ????? ??????? ?????!');
    })->name('activities.rate');

}); // ????? ?????? auth ????????

require __DIR__.'/auth.php';
