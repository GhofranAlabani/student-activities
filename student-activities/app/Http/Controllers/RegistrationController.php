<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    /**
     * تسجيل الطالب في نشاط
     */
    public function store($id)
    {
        $activity = Activity::findOrFail($id);
        
        // التحقق من أن المستخدم مسجل دخول
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'يجب تسجيل الدخول أولاً');
        }
        
        // التحقق من حالة النشاط
        if ($activity->status !== 'مفتوح') {
            return back()->with('error', 'التسجيل في هذا النشاط مغلق');
        }
        
        // التحقق من عدد المشاركين
        if ($activity->max_participants && $activity->users()->count() >= $activity->max_participants) {
            return back()->with('error', 'اكتمل العدد الأقصى للمشاركة');
        }
        
        // ✅ التحقق من أن الطالب غير مسجل مسبقاً (باستخدام جدول registrations مباشرة)
        $alreadyRegistered = DB::table('registrations')
            ->where('activity_id', $activity->id)
            ->where('student_id', auth()->id())
            ->exists();
        
        if ($alreadyRegistered) {
            return back()->with('error', 'أنت مسجل مسبقاً في هذا النشاط');
        }
        
        // ✅ تسجيل الطالب في النشاط (باستخدام جدول registrations مباشرة)
        DB::table('registrations')->insert([
            'activity_id' => $activity->id,
            'student_id' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return back()->with('success', 'تم التسجيل في النشاط بنجاح!');
    }
    
    /**
     * إلغاء التسجيل في نشاط
     */
    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        
        // ✅ إلغاء التسجيل (باستخدام جدول registrations مباشرة)
        DB::table('registrations')
            ->where('activity_id', $activity->id)
            ->where('student_id', auth()->id())
            ->delete();
        
        return back()->with('success', 'تم إلغاء التسجيل بنجاح');
    }
}