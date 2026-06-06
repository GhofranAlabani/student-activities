<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    // عرض جميع المشرفين
    public function index()
    {
        $staff = Staff::with('user')->latest()->paginate(15);
        return view('admin.staff.index', compact('staff'));
    }

    // عرض صفحة إضافة مشرف جديد
    public function create()
    {
        return view('admin.staff.create');
    }

    // حفظ مشرف جديد
   public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
        'position' => 'nullable|string|max:255',
        'department' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:20',
        'hire_date' => 'nullable|date',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'staff',
        'email_verified_at' => now(),
    ]);

    Staff::create([
        'user_id' => $user->id,
        'position' => $request->position,
        'department' => $request->department,
        'phone' => $request->phone,
        'hire_date' => $request->hire_date,
        'status' => 'active',
    ]);

    return redirect()->route('admin.staff')->with('success', 'تم إضافة المشرف بنجاح');
}

    // عرض تفاصيل مشرف
    public function show($id)
    {
        $staff = Staff::with('user')->findOrFail($id);
        return view('admin.staff.show', compact('staff'));
    }

    // عرض صفحة تعديل مشرف
    public function edit($id)
    {
        $staff = Staff::with('user')->findOrFail($id);
        return view('admin.staff.edit', compact('staff'));
    }

    // تحديث بيانات مشرف
    public function update(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);

        $request->validate([
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'hire_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        $staff->update([
            'position' => $request->position,
            'department' => $request->department,
            'phone' => $request->phone,
            'hire_date' => $request->hire_date,
            'status' => $request->status,
        ]);

        // تحديث اسم المستخدم وبريده إذا تم تغييرهما
        if ($request->filled('name') || $request->filled('email')) {
            $staff->user->update([
                'name' => $request->name ?? $staff->user->name,
                'email' => $request->email ?? $staff->user->email,
            ]);
        }

        return redirect()->route('admin.staff')->with('success', 'تم تحديث بيانات المشرف بنجاح');
    }

    // حذف مشرف
   public function destroy($id)
     {
    $staff = Staff::findOrFail($id);
    $userId = $staff->user_id;
    
    // حذف سجل المشرف
    $staff->delete();
    
    // حذف المستخدم من جدول users
    User::where('id', $userId)->delete();

    return redirect()->route('admin.staff')->with('success', 'تم حذف المشرف بنجاح');
    }
}