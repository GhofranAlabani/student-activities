<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    // عرض جميع الإعلانات (للأدمن)
    public function index()
    {
        $announcements = Announcement::with(['user', 'activity'])
            ->latest()
            ->paginate(15);
        
        return view('admin.announcements.index', compact('announcements'));
    }

    // عرض صفحة إضافة إعلان
    public function create()
    {
        return view('admin.announcements.create');
    }

    // حفظ إعلان جديد
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:activity,general,warning',
            'activity_id' => 'nullable|exists:activities,id',
            'expires_at' => 'nullable|date|after:today',
        ]);

        Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'activity_id' => $request->activity_id,
            'user_id' => auth()->id(),
            'is_active' => true,
            'expires_at' => $request->expires_at,
        ]);

        return redirect()->route('admin.announcements')
            ->with('success', 'تم إضافة الإعلان بنجاح');
    }

    // تعديل إعلان
    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        return view('admin.announcements.edit', compact('announcement'));
    }

    // تحديث إعلان
    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:activity,general,warning',
            'is_active' => 'required|boolean',
            'expires_at' => 'nullable|date',
        ]);

        $announcement->update($request->all());

        return redirect()->route('admin.announcements')
            ->with('success', 'تم تحديث الإعلان بنجاح');
    }

    // حذف إعلان
    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return redirect()->route('admin.announcements')
            ->with('success', 'تم حذف الإعلان بنجاح');
    }

    // إنشاء إعلان تلقائي عند إضافة نشاط
    public static function createActivityAnnouncement($activity)
    {
        Announcement::create([
            'title' => 'نشاط جديد: ' . $activity->title,
            'content' => 'تم إضافة نشاط جديد: ' . $activity->title . 
                        ($activity->description ? ' - ' . Str::limit($activity->description, 100) : ''),
            'type' => 'activity',
            'activity_id' => $activity->id,
            'user_id' => auth()->id(),
            'is_active' => true,
        ]);
    }
}