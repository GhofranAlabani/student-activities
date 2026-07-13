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
    /**
 * عرض صفحة تعديل الطالب
 */
public function edit($id)
{
    $student = \App\Models\User::findOrFail($id);
    
    // التحقق من أن الطالب مسجل في أنشطة المشرف
    $isRegistered = \App\Models\Registration::where('student_id', $student->id)
        ->whereHas('activity', function($query) {
            $query->where('supervisor_id', auth()->id());
        })
        ->exists();
    
    if (!$isRegistered) {
        return redirect()->route('staff.students.index')
            ->with('error', 'هذا الطالب غير مسجل في أنشطتك');
    }
    
    return view('staff.students.edit', compact('student'));
}

/**
 * تحديث بيانات الطالب
 */
/**
 * تحديث بيانات الطالب
 */
public function update(Request $request, $id)
{
    $student = \App\Models\User::findOrFail($id);
    
    // التحقق من صحة البيانات (بدون phone و student_number)
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'total_points' => 'nullable|integer|min:0',
        'password' => 'nullable|string|min:8|confirmed',
    ]);
    
    // تحديث البيانات الأساسية
    $student->name = $validated['name'];
    $student->email = $validated['email'];
    $student->total_points = $validated['total_points'] ?? 0;
    
    // تحديث كلمة المرور إذا تم تقديمها
    if (!empty($validated['password'])) {
        $student->password = \Illuminate\Support\Facades\Hash::make($validated['password']);
    }
    
    // حفظ التغييرات
    $student->save();
    
    return redirect()->route('staff.students.index')
        ->with('success', 'تم تحديث بيانات الطالب بنجاح! ✅');
}
/**
 * حذف الطالب من قائمة التسجيلات
 */
public function destroy($id)
{
    $student = \App\Models\User::findOrFail($id);
    
    // حذف جميع تسجيلات الطالب في أنشطة المشرف
    \App\Models\Registration::where('student_id', $student->id)
        ->whereHas('activity', function($query) {
            $query->where('supervisor_id', auth()->id());
        })
        ->delete();
    
    return redirect()->route('staff.students.index')
        ->with('success', 'تم حذف الطالب من قائمة التسجيلات بنجاح! ️');
}
}