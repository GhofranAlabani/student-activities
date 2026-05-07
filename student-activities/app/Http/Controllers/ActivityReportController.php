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

        return response()->json($reports);
    }

    public function store(Request $request)
    {
        $request->validate([
            'activity_id'        => 'required|exists:activities,id',
            'summary'            => 'required|string',
            'participants_count' => 'nullable|integer',
            'outcomes'           => 'nullable|string',
        ]);

        $report = ActivityReport::create([
            'activity_id'        => $request->activity_id,
            'submitted_by'       => Auth::id(),
            'summary'            => $request->summary,
            'participants_count' => $request->participants_count,
            'outcomes'           => $request->outcomes,
        ]);

        return response()->json($report, 201);
    }

    public function show(ActivityReport $activityReport)
    {
        return response()->json(
            $activityReport->load(['activity', 'submittedBy'])
        );
    }

    public function destroy(ActivityReport $activityReport)
    {
        $activityReport->delete();
        return response()->json(['message' => 'تم الحذف بنجاح']);
    }
}