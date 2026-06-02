<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class ActivityController extends Controller
{
    /**
     * عرض جميع الأنشطة
     */
    public function index(Request $request)
    {
        $query = Activity::with(['activityType', 'users']);
        
        // البحث
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        // الفلترة حسب النوع
        if ($request->filled('type')) {
            $query->where('type_id', $request->type);
        }
        
        $activities = $query->latest()->paginate(9);
        
        return view('activities.index', compact('activities'));
    }

    /**
     * عرض نموذج إضافة نشاط
     */
    public function create()
    {
        $activityTypes = ActivityType::all();
        return view('activities.create', compact('activityTypes'));
    }

    /**
     * حفظ نشاط جديد
     */
    public function store(Request $request)
    {
        // ✅ ملاحظة: الفورم يرسل 'activity_type_id' لكن الداتابيز فيها 'type_id'
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'activity_type_id' => 'required|exists:activity_types,id', // من الفورم
            'date' => 'required|date',
            'status' => 'required|string',
            'max_participants' => 'nullable|integer|min:1',
            'points' => 'nullable|integer|min:0',
            'location' => 'nullable|string|max:255',
            'time' => 'nullable',
            'end_time' => 'nullable',
            'online_link' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // ✅ تحويل activity_type_id (من الفورم) إلى type_id (للداتابيز)
        if (isset($validated['activity_type_id'])) {
            $validated['type_id'] = $validated['activity_type_id'];
            unset($validated['activity_type_id']);
        }

        // معالجة الصورة
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('activities', 'public');
        }

        // إضافة created_by إذا كان العمود موجود
        if (auth()->check() && Schema::hasColumn('activities', 'created_by')) {
            $validated['created_by'] = auth()->id();
        }

        Activity::create($validated);

        return redirect()->route('activities.index')
            ->with('success', '✅ تمت إضافة النشاط بنجاح');
    }

    /**
     * عرض تفاصيل نشاط
     */
    public function show($id)
    {
        $activity = Activity::with(['activityType', 'users', 'creator', 'registrations'])
            ->findOrFail($id);
        
        return view('activities.show', compact('activity'));
    }

    /**
     * عرض نموذج تعديل نشاط
     */
    public function edit($id)
    {
        $activity = Activity::findOrFail($id);
        $activityTypes = ActivityType::all();
        return view('activities.edit', compact('activity', 'activityTypes'));
    }

    /**
     * ✅ تحديث نشاط - الكود المصحح
     */
    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'activity_type_id' => 'required|exists:activity_types,id',
            'date' => 'required|date',
            'status' => 'required|string',
            'max_participants' => 'nullable|integer|min:1',
            'points' => 'nullable|integer|min:0',
            'location' => 'nullable|string|max:255',
            'time' => 'nullable',
            'end_time' => 'nullable',
            'online_link' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // ✅ تحويل activity_type_id إلى type_id (مهم جداً للتحديث)
        if (isset($validated['activity_type_id'])) {
            $validated['type_id'] = $validated['activity_type_id'];
            unset($validated['activity_type_id']);
        }

        // معالجة الصورة الجديدة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة
            if ($activity->image && Storage::disk('public')->exists($activity->image)) {
                Storage::disk('public')->delete($activity->image);
            }
            $validated['image'] = $request->file('image')->store('activities', 'public');
        }

        // ✅ التحديث الصحيح (ليس حذف!)
        $activity->update($validated);

        return redirect()->route('activities.index')
            ->with('success', '✅ تم تعديل النشاط بنجاح');
    }

    /**
     * حذف نشاط
     */
    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        
        // حذف الصورة المرتبطة
        if ($activity->image && Storage::disk('public')->exists($activity->image)) {
            Storage::disk('public')->delete($activity->image);
        }
        
        $activity->delete();
        
        return redirect()->route('activities.index')
            ->with('success', '✅ تم حذف النشاط بنجاح');
    }

    /**
     * إضافة/إزالة من المفضلة
     */
    public function toggleFavorite($id)
    {
        $favorite = \App\Models\Favorite::where('user_id', auth()->id())
            ->where('activity_id', $id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return back()->with('success', 'تمت إزالة النشاط من المفضلة');
        } else {
            \App\Models\Favorite::create([
                'user_id' => auth()->id(),
                'activity_id' => $id
            ]);
            return back()->with('success', 'تمت إضافة النشاط للمفضلة');
        }
    }
}