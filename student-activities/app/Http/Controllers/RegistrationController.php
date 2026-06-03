<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegistrationController extends Controller
{
    /**
     * تسجيل طالب في نشاط
     */
    public function store(Request $request, $activityId)
    {
        try {
            // ✅ التحقق من أن المستخدم مسجل دخول
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
            }

            $activity = Activity::withCount('users')->findOrFail($activityId);
            $user = Auth::user();

            // ✅ التحقق من حالة النشاط
            if ($activity->status !== 'مفتوح' && $activity->status !== 'active') {
                return redirect()->back()->with('error', '❌ هذا النشاط غير متاح للتسجيل حالياً');
            }

            // ✅ التحقق من تاريخ النشاط (لم ينتهِ)
            if ($activity->date && \Carbon\Carbon::parse($activity->date)->isPast()) {
                return redirect()->back()->with('error', '❌ انتهى موعد هذا النشاط');
            }

            // ✅ التحقق من سعة النشاط
            if ($activity->max_participants && $activity->users_count >= $activity->max_participants) {
                return redirect()->back()->with('error', '❌ عذراً، اكتمل عدد المشاركين في هذا النشاط');
            }

            // ✅ التحقق من عدم التسجيل المسبق (باستخدام العلاقة)
            if ($activity->users->contains($user->id)) {
                return redirect()->back()->with('error', '⚠️ أنت مسجل مسبقاً في هذا النشاط');
            }

            // ✅ التسجيل باستخدام Eloquent (أفضل ممارسة)
            $activity->users()->attach($user->id, [
                'registered_at' => now(),
                'status' => 'confirmed' // حقل اختياري إذا كان موجود في جدول registrations
            ]);

            // ✅ إضافة نقاط الطالب إذا كان النشاط يحتوي على نقاط
            if ($activity->points && $activity->points > 0) {
                $user->increment('points', $activity->points);
            }

            // ✅ تسجيل الحدث في اللوج (اختياري - مفيد للتتبع)
            Log::info("Student {$user->id} registered in activity {$activityId}");

            return redirect()->back()->with('success', '🎉 مبروك! تم تسجيلك في النشاط بنجاح');

        } catch (\Exception $e) {
            // ✅ تسجيل الخطأ في اللوج للمطور
            Log::error('Registration error: ' . $e->getMessage());
            
            return redirect()->back()->with('error', '⚠️ حدث خطأ غير متوقع أثناء التسجيل. يرجى المحاولة لاحقاً');
        }
    }

    /**
     * إلغاء تسجيل طالب من نشاط
     */
    public function destroy(Request $request, $activityId)
    {
        try {
            $user = Auth::user();
            
            // ✅ التحقق من وجود التسجيل قبل الحذف
            $activity = Activity::findOrFail($activityId);
            
            if (!$activity->users->contains($user->id)) {
                return redirect()->back()->with('error', '⚠️ أنت غير مسجل في هذا النشاط');
            }

            // ✅ إلغاء التسجيل باستخدام Eloquent
            $activity->users()->detach($user->id);

            // ✅ (اختياري) خصم النقاط إذا أردت
            // if ($activity->points && $activity->points > 0) {
            //     $user->decrement('points', $activity->points);
            // }

            Log::info("Student {$user->id} unregistered from activity {$activityId}");

            return redirect()->back()->with('success', '✅ تم إلغاء تسجيلك بنجاح');

        } catch (\Exception $e) {
            Log::error('Unregister error: ' . $e->getMessage());
            
            return redirect()->back()->with('error', '⚠️ حدث خطأ أثناء إلغاء التسجيل');
        }
    }

    /**
     * ✅ دالة إضافية: عرض تسجيلات طالب محدد (للمدير)
     */
    public function index(Request $request)
    {
        // هذه الدالة اختيارية - تستخدم في لوحة تحكم المدير
        $query = \App\Models\Registration::with(['user', 'activity']);
        
        if ($request->filled('student')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->student.'%');
            });
        }
        
        if ($request->filled('activity')) {
            $query->whereHas('activity', function($q) use ($request) {
                $q->where('title', 'like', '%'.$request->activity.'%');
            });
        }
        
        $registrations = $query->latest()->paginate(15);
        
        return view('admin.registrations.index', compact('registrations'));
    }
}