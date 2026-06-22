<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StaffActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::where('supervisor_id', Auth::id())
            ->with(['activityType'])
            ->withCount('registrations')
            ->latest()
            ->paginate(10);
        
        return view('staff.activities.index', compact('activities'));
    }

    public function create()
    {
        $activityTypes = ActivityType::all();
        return view('staff.activities.create', compact('activityTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type_id' => 'required|exists:activity_types,id',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required',
            'max_participants' => 'nullable|integer|min:1',
            'points' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        // رفع الصورة
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('activities', 'public');
        }

        // إضافة المشرف
        $validated['supervisor_id'] = Auth::id();
        $validated['status'] = 'مفتوح';

        Activity::create($validated);

        return redirect()->route('staff.activities.index')
            ->with('success', 'تم إنشاء النشاط بنجاح');
    }

    public function show(Activity $activity)
    {
        // التحقق أن النشاط يخص المشرف
        if ($activity->supervisor_id !== Auth::id()) {
            abort(403, 'هذا النشاط ليس تحت إشرافك');
        }

        $activity->load(['activityType', 'registrations.user']);
        
        return view('staff.activities.show', compact('activity'));
    }

    public function edit(Activity $activity)
    {
        if ($activity->supervisor_id !== Auth::id()) {
            abort(403, 'هذا النشاط ليس تحت إشرافك');
        }

        $activityTypes = ActivityType::all();
        
        return view('staff.activities.edit', compact('activity', 'activityTypes'));
    }

    public function update(Request $request, Activity $activity)
    {
        if ($activity->supervisor_id !== Auth::id()) {
            abort(403, 'هذا النشاط ليس تحت إشرافك');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type_id' => 'required|exists:activity_types,id',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required',
            'max_participants' => 'nullable|integer|min:1',
            'points' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        // رفع الصورة الجديدة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة
            if ($activity->image) {
                Storage::disk('public')->delete($activity->image);
            }
            $validated['image'] = $request->file('image')->store('activities', 'public');
        }

        $activity->update($validated);

        return redirect()->route('staff.activities.index')
            ->with('success', 'تم تحديث النشاط بنجاح');
    }

    public function destroy(Activity $activity)
    {
        if ($activity->supervisor_id !== Auth::id()) {
            abort(403, 'هذا النشاط ليس تحت إشرافك');
        }

        // حذف الصورة
        if ($activity->image) {
            Storage::disk('public')->delete($activity->image);
        }

        $activity->delete();

        return redirect()->route('staff.activities.index')
            ->with('success', 'تم حذف النشاط بنجاح');
    }
}