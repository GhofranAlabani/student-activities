<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Registration;
use App\Models\PointsHistory;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    // =========================
    // 📌 تسجيل طالب في نشاط
    // =========================
    public function register($activity_id)
    {
        $user = Auth::user();

        // 🎓 التأكد أنه طالب
        if ($user->role !== 'student') {
            abort(403, 'Only students can register');
        }

        // 🎯 جلب النشاط
        $activity = Activity::findOrFail($activity_id);

        // ❌ منع التسجيل المكرر
        $exists = Registration::where('user_id', $user->id)
            ->where('activity_id', $activity_id)
            ->first();

        if ($exists) {
            return back()->with('error', 'You are already registered');
        }

        // 🚫 التحقق من امتلاء النشاط
        $registeredCount = Registration::where('activity_id', $activity_id)
            ->where('status', 'approved')
            ->count();

        if ($activity->max_participants && $registeredCount >= $activity->max_participants) {
            return back()->with('error', 'Activity is full');
        }

        // 🧾 إنشاء التسجيل (pending)
        $registration = Registration::create([
            'user_id' => $user->id,
            'activity_id' => $activity_id,
            'status' => 'pending'
        ]);

        // 🔔 إشعار للطالب
        Notification::create([
            'user_id' => $user->id,
            'title' => 'Registration Submitted',
            'message' => 'Your registration for ' . $activity->title . ' is pending approval',
            'type' => 'registration'
        ]);

        return back()->with('success', 'Registration submitted successfully');
    }

    // =========================
    // ✔️ قبول التسجيل (مشرف)
    // =========================
    public function approve($id)
    {
        $registration = Registration::findOrFail($id);

        $registration->status = 'approved';
        $registration->save();

        $user = $registration->user;
        $activity = $registration->activity;

        // 🎯 إضافة نقاط للمستخدم
        $user->points += $activity->points;
        $user->save();

        // 📊 تسجيل النقاط في التاريخ
        PointsHistory::create([
            'user_id' => $user->id,
            'activity_id' => $activity->id,
            'points' => $activity->points,
            'reason' => 'Approved registration in activity'
        ]);

        // 🔔 إشعار
        Notification::create([
            'user_id' => $user->id,
            'title' => 'Registration Approved',
            'message' => 'You have been approved for ' . $activity->title,
            'type' => 'registration'
        ]);

        return back()->with('success', 'Registration approved');
    }

    // =========================
    // ❌ رفض التسجيل (مشرف)
    // =========================
    public function reject($id)
    {
        $registration = Registration::findOrFail($id);

        $registration->status = 'rejected';
        $registration->save();

        $user = $registration->user;
        $activity = $registration->activity;

        // 🔔 إشعار
        Notification::create([
            'user_id' => $user->id,
            'title' => 'Registration Rejected',
            'message' => 'Your registration for ' . $activity->title . ' was rejected',
            'type' => 'registration'
        ]);

        return back()->with('error', 'Registration rejected');
    }
}