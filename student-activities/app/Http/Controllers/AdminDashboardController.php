<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalActivities = Activity::count();
        $totalStudents = User::count(); 
        $totalRegistrations = 0;
        try {
            $totalRegistrations = DB::table('registrations')->count();
        } catch (\Exception $e) {
            $totalRegistrations = 0;
        }

        return view('admin.dashboard', [
            'totalActivities' => $totalActivities,
            'totalStudents' => $totalStudents,
            'totalRegistrations' => $totalRegistrations
        ]);
    }

    public function showRegistrations($id)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة.');
        }
        $activity = \App\Models\Activity::with(['users', 'activityType'])->findOrFail($id);
        $students = $activity->users;
        
        return view('admin.registrations', compact('activity', 'students'));
    }

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

    public function showAllStudents(Request $request)
    {
        $query = User::with('activities');
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        $students = $query->latest()->paginate(15);
        
        return view('admin.students', compact('students'));
    }
}