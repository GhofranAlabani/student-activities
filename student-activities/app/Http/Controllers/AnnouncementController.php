<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    // عرض جميع الإعلانات (للأدمن)
    public function index()
    {
       $announcements = Announcement::with(['creator'])
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
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'type' => 'nullable|string|in:activity,warning,general',
        'is_active' => 'boolean',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after:start_date',
    ]);

    $validated['created_by'] = auth()->id();
    $validated['is_active'] = $request->has('is_active');

    Announcement::create($validated);

    return redirect()->route('admin.announcements')
        ->with('success', 'تم إنشاء الإعلان بنجاح!');
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
    
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'type' => 'nullable|string|in:activity,warning,general',
        'is_active' => 'boolean',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after:start_date',
    ]);

    $validated['is_active'] = $request->has('is_active');

    $announcement->update($validated);

    return redirect()->route('admin.announcements')
        ->with('success', 'تم تحديث الإعلان بنجاح!');
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