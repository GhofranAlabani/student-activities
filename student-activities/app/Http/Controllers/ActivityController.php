<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityType;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
{
    $query = Activity::with('activityType');
    
    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%')
              ->orWhere('description', 'like', '%' . $request->search . '%');
    }
    
    if ($request->filled('type')) {
        $query->where('activity_type_id', $request->type);
    }
    
    $activities = $query->latest()->paginate(9);
    
    // ✅ ضروري عشان القلب يعرف وش مسجل مسبقاً
    $favoriteIds = \App\Models\Favorite::where('user_id', auth()->id())->pluck('activity_id')->toArray();
    
    return view('activities.index', compact('activities', 'favoriteIds'));
}
    public function show($id)
{
    $activity = Activity::with(['activityType', 'users', 'creator'])->findOrFail($id);
    return view('activities.show', compact('activity'));
}
public function toggleFavorite($id)
{
    // استخدام مودل المفضلة مباشرة
    $favorite = \App\Models\Favorite::where('user_id', auth()->id())
        ->where('activity_id', $id)
        ->first();

    if ($favorite) {
        // إذا كان موجود سابقاً، نحذفه (إلغاء المفضلة)
        $favorite->delete();
        return back()->with('success', 'تمت إزالة النشاط من المفضلة');
    } else {
        // إذا غير موجود، نضيفه
        \App\Models\Favorite::create([
            'user_id' => auth()->id(),
            'activity_id' => $id
        ]);
        return back()->with('success', 'تمت إضافة النشاط للمفضلة');
    }
}
}