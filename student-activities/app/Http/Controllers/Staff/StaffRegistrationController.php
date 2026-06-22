<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffRegistrationController extends Controller
{
    public function index(Activity $activity)
    {
        if ($activity->supervisor_id !== Auth::id()) {
            abort(403, 'هذا النشاط ليس تحت إشرافك');
        }

        $registrations = Registration::where('activity_id', $activity->id)
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('staff.registrations.index', compact('activity', 'registrations'));
    }

    public function approve(Registration $registration)
    {
        $activity = $registration->activity;
        
        if ($activity->supervisor_id !== Auth::id()) {
            abort(403, 'هذا النشاط ليس تحت إشرافك');
        }

        $registration->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return back()->with('success', 'تم قبول التسجيل بنجاح');
    }

    public function reject(Registration $registration)
    {
        $activity = $registration->activity;
        
        if ($activity->supervisor_id !== Auth::id()) {
            abort(403, 'هذا النشاط ليس تحت إشرافك');
        }

        $registration->update([
            'status' => 'rejected',
            'rejected_at' => now(),
        ]);

        return back()->with('success', 'تم رفض التسجيل بنجاح');
    }
}