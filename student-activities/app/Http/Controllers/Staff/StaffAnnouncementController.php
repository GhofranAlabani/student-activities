<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffAnnouncementController extends Controller
{
    /**
     * عرض قائمة الإعلانات
     */
    public function index()
    {
        $announcements = Announcement::where('created_by', Auth::id())
            ->latest()
            ->get();
        
        return view('staff.announcements.index', compact('announcements'));
    }

    /**
     * إنشاء إعلان جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,urgent,info',
            'is_active' => 'nullable|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'is_active' => $request->has('is_active'),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('staff.announcements.index')
            ->with('success', 'تم إنشاء الإعلان بنجاح!');
    }

    /**
     * حذف إعلان
     */
    public function destroy(Announcement $announcement)
    {
        if ($announcement->created_by !== Auth::id()) {
            abort(403, 'هذا الإعلان ليس لك');
        }

        $announcement->delete();

        return redirect()->route('staff.announcements.index')
            ->with('success', 'تم حذف الإعلان بنجاح!');
    }
}