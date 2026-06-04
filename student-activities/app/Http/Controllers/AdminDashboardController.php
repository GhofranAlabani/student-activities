<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * عرض لوحة تحكم المدير
     */
    public function index()
    {
        $totalActivities = Activity::count();
        $totalStudents = User::where('role', 'student')->count();
        $totalRegistrations = DB::table('registrations')->count();

        return view('admin.dashboard', compact(
            'totalActivities',
            'totalStudents',
            'totalRegistrations'
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
            ->join('users', 'registrations.user_id', '=', 'users.id')
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