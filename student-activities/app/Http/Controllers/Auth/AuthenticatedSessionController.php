<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display login page
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 🔐 تسجيل الدخول والتحقق من البيانات
        $request->authenticate();

        // 🔄 إعادة إنشاء session للحماية
        $request->session()->regenerate();

        // 👤 جلب المستخدم الحالي
        $user = Auth::user();

        if (!$user) {
            return redirect('/login');
        }

        // 🎯 التوجيه حسب الدور
        if ($user->role === 'student') {
            return redirect('/student/dashboard');
        }

        if ($user->role === 'supervisor') {
            return redirect('/supervisor/dashboard');
        }

        // fallback (احتياطي)
        return redirect('/dashboard');
    }

    /**
     * Logout
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}