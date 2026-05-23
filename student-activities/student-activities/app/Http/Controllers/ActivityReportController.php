<?php

namespace App\Http\Controllers;

use App\Models\ActivityReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityReportController extends Controller
{
    public function index()
    {
        $reports = ActivityReport::with(['activity', 'submittedBy'])
            ->latest()
            ->get();

        return view('reports.index', compact('reports'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'activity_id'        => 'required|exists:activities,id',
            'summary'            => 'required|string',
            'participants_count' => 'nullable|integer',
            'outcomes'           => 'nullable|string',
        ]);

        ActivityReport::create([
            'activity_id'        => $request->activity_id,
            'submitted_by'       => Auth::id(),
            'summary'            => $request->summary,
            'participants_count' => $request->participants_count,
            'outcomes'           => $request->outcomes,
        ]);

        return back()->with('success', 'تم إرسال التقرير بنجاح ✅');
    }

    public function show(ActivityReport $activityReport)
    {
        $activityReport->load(['activity', 'submittedBy']);

        return view('reports.show', compact('activityReport'));
    }

    public function destroy(ActivityReport $activityReport)
    {
        $activityReport->delete();

        return back()->with('success', 'تم حذف التقرير بنجاح ');
    }
}