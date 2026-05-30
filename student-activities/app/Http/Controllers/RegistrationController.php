<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    public function store($activityId)
    {
        try {
            $activity = Activity::findOrFail($activityId);
            $user = Auth::user();

            // التحقق من أن النشاط متاح
            if ($activity->status !== '?????') {
                return redirect()->back()->with('error', 'هذا النشاط غير متاح للتسجيل');
            }

            // التحقق من العدد
            if ($activity->max_participants && $activity->users()->count() >= $activity->max_participants) {
                return redirect()->back()->with('error', 'عذراً، اكتمل عدد المشاركين');
            }

            // التحقق من عدم التسجيل المسبق
            $alreadyRegistered = DB::table('registrations')
                ->where('student_id', $user->id)
                ->where('activity_id', $activity->id)
                ->exists();

            if ($alreadyRegistered) {
                return redirect()->back()->with('error', 'أنت مسجل مسبقاً في هذا النشاط');
            }

            // التسجيل في قاعدة البيانات
            DB::table('registrations')->insert([
                'student_id' => $user->id,
                'activity_id' => $activity->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // إضافة النقاط إذا موجودة
            if ($activity->points) {
                $user->increment('points', $activity->points);
            }

            return redirect()->back()->with('success', '🎉 تم التسجيل في النشاط بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء التسجيل. حاول مرة أخرى.');
        }
    }

    public function destroy($activityId)
    {
        try {
            DB::table('registrations')
                ->where('student_id', Auth::id())
                ->where('activity_id', $activityId)
                ->delete();

            return redirect()->back()->with('success', 'تم إلغاء التسجيل بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إلغاء التسجيل');
        }
    }
}
