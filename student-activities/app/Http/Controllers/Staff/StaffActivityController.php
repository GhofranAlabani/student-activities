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
    $activityTypes = \App\Models\ActivityType::all();
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
        'end_time' => 'nullable',
        'max_participants' => 'nullable|integer|min:1',
        'points' => 'nullable|integer|min:0',
        'online_link' => 'nullable|url',
        'certificate' => 'nullable',
        'image' => 'nullable|image|max:2048',
        // الحقول الديناميكية
        'speakers' => 'nullable|array',
        'speakers.*' => 'nullable|string|max:255',
        'hall_capacity' => 'nullable|integer',
        'agenda' => 'nullable|string',
        'live_stream_link' => 'nullable|url',
        'prize_value' => 'nullable|string',
        'team_size' => 'nullable|integer',
        'judging_criteria' => 'nullable|string',
        'submission_deadline' => 'nullable|date',
        'duration_hours' => 'nullable|numeric',
        'prerequisites' => 'nullable|string',
        'materials_list' => 'nullable|string',
        'required_skills' => 'nullable|string',
        'transportation' => 'nullable|string',
        'community_impact' => 'nullable|string',
        'uniform_provided' => 'nullable',
    ]);

    // رفع الصورة
    if ($request->hasFile('image')) {
        $validated['image'] = $request->file('image')->store('activities', 'public');
    }

    // إضافة المشرف والحالة
    $validated['supervisor_id'] = Auth::id();
    $validated['created_by'] = Auth::id();
    $validated['status'] = 'مفتوح';
    $validated['certificate'] = $request->has('certificate') ? 1 : 0;

    // جمع البيانات الديناميكية في extra_data
    $extraData = [];
    
    // بيانات المؤتمر
    if ($request->filled('speakers')) {
        $extraData['speakers'] = array_filter($request->speakers);
    }
    if ($request->filled('hall_capacity')) {
        $extraData['hall_capacity'] = $request->hall_capacity;
    }
    if ($request->filled('agenda')) {
        $extraData['agenda'] = $request->agenda;
    }
    if ($request->filled('live_stream_link')) {
        $extraData['live_stream_link'] = $request->live_stream_link;
    }
    
    // بيانات المسابقة
    if ($request->filled('prize_value')) {
        $extraData['prize_value'] = $request->prize_value;
    }
    if ($request->filled('team_size')) {
        $extraData['team_size'] = $request->team_size;
    }
    if ($request->filled('judging_criteria')) {
        $extraData['judging_criteria'] = $request->judging_criteria;
    }
    if ($request->filled('submission_deadline')) {
        $extraData['submission_deadline'] = $request->submission_deadline;
    }
    
    // بيانات ورشة العمل
    if ($request->filled('duration_hours')) {
        $extraData['duration_hours'] = $request->duration_hours;
    }
    if ($request->filled('prerequisites')) {
        $extraData['prerequisites'] = $request->prerequisites;
    }
    if ($request->filled('materials_list')) {
        $extraData['materials_list'] = $request->materials_list;
    }
    
    // بيانات التطوع
    if ($request->filled('required_skills')) {
        $extraData['required_skills'] = $request->required_skills;
    }
    if ($request->filled('transportation')) {
        $extraData['transportation'] = $request->transportation;
    }
    if ($request->filled('community_impact')) {
        $extraData['community_impact'] = $request->community_impact;
    }
    if ($request->has('uniform_provided')) {
        $extraData['uniform_provided'] = true;
    }

    // إزالة الحقول الديناميكية من validated قبل الإنشاء
    $dynamicFields = [
        'speakers', 'hall_capacity', 'agenda', 'live_stream_link',
        'prize_value', 'team_size', 'judging_criteria', 'submission_deadline',
        'duration_hours', 'prerequisites', 'materials_list',
        'required_skills', 'transportation', 'community_impact', 'uniform_provided'
    ];
    
    foreach ($dynamicFields as $field) {
        unset($validated[$field]);
    }

    // حفظ extra_data كـ JSON
    if (!empty($extraData)) {
        $validated['extra_data'] = json_encode($extraData, JSON_UNESCAPED_UNICODE);
    }

    // إنشاء النشاط
    Activity::create($validated);

    return redirect()->route('staff.activities.index')
        ->with('success', 'تم إنشاء النشاط بنجاح! 🎉');
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