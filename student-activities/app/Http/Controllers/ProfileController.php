<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * عرض صفحة الملف الشخصي
     */
    public function show(): View
    {
        return view('profile');
    }

    /**
     * تحديث المعلومات الشخصية أو كلمة المرور
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // 1. إذا كان الطلب لتحديث كلمة المرور
        if ($request->has('update_password')) {
            $validated = $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', Password::defaults(), 'confirmed'],
            ]);

            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            return back()->with('success', '✅ تم تغيير كلمة المرور بنجاح!');
        }

        // 2. تحديث المعلومات الشخصية
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return back()->with('success', '✅ تم تحديث المعلومات بنجاح!');
    }

    /**
     * حذف حساب المستخدم (اختياري)
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}