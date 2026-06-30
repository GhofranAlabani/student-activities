<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffStudentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // جلب كل الطلاب المسجلين في أنشطة المشرف
        $students = User::where('role', 'student')
            ->whereHas('registrations.activity', function($q) use ($user) {
                $q->where('supervisor_id', $user->id);
            })
            ->with(['registrations' => function($q) use ($user) {
                $q->whereHas('activity', function($q2) use ($user) {
                    $q2->where('supervisor_id', $user->id);
                })->with('activity');
            }])
            ->latest()
            ->paginate(20);
        
        // إحصائيات
        $totalStudents = $students->total();
        $totalRegistrations = Registration::whereHas('activity', function($q) use ($user) {
            $q->where('supervisor_id', $user->id);
        })->count();
        
        return view('staff.students.index', compact(
            'students',
            'totalStudents',
            'totalRegistrations'
        ));
    }
}