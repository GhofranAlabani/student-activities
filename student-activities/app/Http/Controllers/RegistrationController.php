<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    public function store(Request $request, $activityId)
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
            }

            $activity = Activity::findOrFail($activityId);
            $user = Auth::user();

            if ($activity->status !== 'مفتوح') {
                return redirect()->back()->with('error', 'هذا النشاط غير متاح للتسجيل');
            }

            $alreadyRegistered = DB::table('registrations')
                ->where('student_id', $user->id)
                ->where('activity_id', $activity->id)
                ->exists();

            if ($alreadyRegistered) {
                return redirect()->back()->with('error', 'أنت مسجل مسبقاً في هذا النشاط');
            }

            if ($activity->max_participants && DB::table('registrations')->where('activity_id', $activity->id)->count() >= $activity->max_participants) {
                return redirect()->back()->with('error', 'اكتمل عدد المشاركين');
            }

            DB::table('registrations')->insert([
                'student_id' => $user->id,
                'activity_id' => $activity->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($activity->points && $activity->points > 0) {
                DB::table('users')->where('id', $user->id)->increment('points', $activity->points);
            }

            return redirect()->back()->with('success', '🎉 تم التسجيل في النشاط بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء التسجيل. يرجى المحاولة لاحقاً');
        }
    }

    public function destroy(Request $request, $activityId)
    {
        try {
            $user = Auth::user();

            DB::table('registrations')
                ->where('student_id', $user->id)
                ->where('activity_id', $activityId)
                ->delete();

            return redirect()->back()->with('success', 'تم إلغاء تسجيلك بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إلغاء التسجيل');
        }
    }
}
