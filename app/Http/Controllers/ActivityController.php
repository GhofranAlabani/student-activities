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
}