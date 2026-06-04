<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ActivityReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminDashboardController;

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

// ???? ???? ??????
Route::get('/student/dashboard', function () {
    return view('student.dashboard', [
        'totalActivities' => \App\Models\Activity::count(),
        'totalStudents' => \App\Models\User::count(),
        'totalRegistrations' => 0
    ]);
})->middleware(['auth'])->name('student.dashboard');

// ?????? ??????
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/activity/{id}/registrations', [AdminDashboardController::class, 'showRegistrations'])->name('admin.registrations');
    Route::get('/admin/students', [AdminDashboardController::class, 'showAllStudents'])->name('admin.students');
    Route::get('/admin/all-registrations', [AdminDashboardController::class, 'allRegistrations'])->name('admin.all-registrations');
});

// ?????? ??????? ??????
Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
Route::get('/activities/create', [ActivityController::class, 'create'])->name('activities.create');
Route::get('/activities/{id}', [ActivityController::class, 'show'])->name('activities.show');

// ?????? ????? ????? ????
Route::middleware('auth')->group(function () {
    // ?????? ????? ??????
Route::get('/admin/staff', function () {
    return view('admin.staff');
})->name('admin.staff');

// ?????? ????????? ??????????
Route::get('/admin/announcements', function () {
    return view('admin.announcements');
})->name('admin.announcements');

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

// ? ??? ?? ???? ??? ????? ?? ??????? ??????
require __DIR__.'/auth.php';
