<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityType;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * عرض جميع الأنشطة مع البحث والفلترة
     */
    public function index(Request $request)
    {
        $query = Activity::with(['activityType', 'users']);
        
        // Search - البحث
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        // Filter by type - الفلترة حسب النوع
        if ($request->filled('type')) {
            $query->where('type_id', $request->type);
        }
        
        $activities = $query->latest()->paginate(9);
        
        // جلب الأنشطة المفضلة للمستخدم الحالي
        $favoriteIds = \App\Models\Favorite::where('user_id', auth()->id())
            ->pluck('activity_id')
            ->toArray();
        
        return view('activities.index', compact('activities', 'favoriteIds'));
    }

    /**
     * عرض تفاصيل نشاط معين
     */
    public function show($id)
    {
        $activity = Activity::with(['activityType', 'users', 'creator', 'registrations'])
            ->findOrFail($id);
        
        return view('activities.show', compact('activity'));
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
            // إذا كان موجود، احذفه (إلغاء المفضلة)
            $favorite->delete();
            return back()->with('success', 'تمت إزالة النشاط من المفضلة');
        } else {
            // إذا غير موجود، أضفه
            \App\Models\Favorite::create([
                'user_id' => auth()->id(),
                'activity_id' => $id
            ]);
            return back()->with('success', 'تمت إضافة النشاط للمفضلة');
        }
    }
public function create()
    {
        return view('activities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'activity_type_id' => 'required|exists:activity_types,id',
            'date' => 'required|date',
            'status' =>'required|in:مفتوح,مغلق,منتهي,ملغي',
            'max_participants' => 'nullable|integer|min:1',
            'points' => 'nullable|integer|min:0',
            'location' => 'nullable|string|max:255',
            'time' => 'nullable',
            'end_time' => 'nullable',
            'online_link' => 'nullable|url',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
       

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('activities', 'public');
        }

        Activity::create($data);

        return redirect()->route('activities.index')->with('success', 'تمت إضافة النشاط بنجاح');
    }

    public function edit($id)
    {
        $activity = Activity::findOrFail($id);
        return view('activities.edit', compact('activity'));
    }

    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'activity_type_id' => 'required|exists:activity_types,id',
            'date' => 'required|date',
            'status' => 'required|in:مفتوح,مغلق,منتهي,ملغي',
            'max_participants' => 'nullable|integer|min:1',
            'points' => 'nullable|integer|min:0',
            'location' => 'nullable|string|max:255',
            'time' => 'nullable',
            'end_time' => 'nullable',
            'online_link' => 'nullable|url',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('activities', 'public');
        }

        $activity->update($data);

        return redirect()->route('activities.index')->with('success', 'تم تعديل النشاط بنجاح');
    }

    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        $activity->delete();
        return redirect()->route('activities.index')->with('success', 'تم حذف النشاط بنجاح');
    }
}