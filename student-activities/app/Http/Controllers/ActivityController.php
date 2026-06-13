<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    /**
     * عرض جميع الأنشطة (للطلاب والزوار)
     */
    public function index(Request $request)
    {
        $query = Activity::with(['activityType', 'users', 'ratings', 'creator']);
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('type')) {
            $query->where('type_id', $request->type);
        }
        
        $activities = $query->latest()->paginate(9);
        
        $favoriteIds = auth()->check() 
            ? auth()->user()->favorites()->pluck('activity_id')->toArray() 
            : [];

        $registeredIds = auth()->check() 
            ? DB::table('registrations')->where('student_id', auth()->id())->pluck('activity_id')->toArray()
            : [];
        
        return view('activities.index', compact('activities', 'favoriteIds', 'registeredIds'));
    }

    /**
     * عرض نموذج إضافة نشاط (للمشرفين/المدير)
     */
    /**
 * عرض نموذج إضافة نشاط (للمشرفين/المدير)
 */
public function create()
{
    $activityTypes = ActivityType::all();
    
    // جلب المشرفين والمديرين فقط
    $supervisors = \App\Models\User::whereIn('role', ['admin', 'staff'])->get();
    
    return view('activities.create', compact('activityTypes', 'supervisors'));
}

    /**
     * حفظ نشاط جديد
     */
    public function store(Request $request)
    {
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

        // تحويل activity_type_id إلى type_id
        if (isset($validated['activity_type_id'])) {
            $validated['type_id'] = $validated['activity_type_id'];
            unset($validated['activity_type_id']);
        }

        // معالجة الصورة
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('activities', 'public');
        }

        // إضافة created_by (المنظم/المشرف)
        if (auth()->check() && Schema::hasColumn('activities', 'created_by')) {
            $validated['created_by'] = auth()->id();
        }

        Activity::create($validated);

        return redirect()->route('activities.index')
            ->with('success', '✅ تمت إضافة النشاط بنجاح');
    }

    /**
     * عرض تفاصيل نشاط مع التقييمات ⭐
     */
    public function show($id)
    {
        $activity = Activity::with([
            'activityType', 
            'users', 
            'creator', 
            'registrations',
            'ratings.user'
        ])
        ->withAvg('ratings as average_rating', 'rating')
        ->withCount('ratings as ratings_count')
        ->findOrFail($id);
        
        // التحقق هل الطالب الحالي قيّم هذا النشاط من قبل؟
        $userRating = null;
        if (auth()->check()) {
            $userRating = $activity->ratings->firstWhere('user_id', auth()->id());
        }
        
        return view('activities.show', compact('activity', 'userRating'));
    }

    /**
     * عرض نموذج تعديل نشاط
     */
    /**
 * عرض نموذج تعديل نشاط
 */
public function edit($id)
{
    $activity = Activity::findOrFail($id);
    $activityTypes = ActivityType::all();
    
    // جلب المشرفين والمديرين فقط
    $supervisors = \App\Models\User::whereIn('role', ['admin', 'staff'])->get();
    
    return view('activities.edit', compact('activity', 'activityTypes', 'supervisors'));
}

    /**
     * تحديث نشاط
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

        // تحويل activity_type_id إلى type_id
        if (isset($validated['activity_type_id'])) {
            $validated['type_id'] = $validated['activity_type_id'];
            unset($validated['activity_type_id']);
        }

        // معالجة الصورة الجديدة
        if ($request->hasFile('image')) {
            if ($activity->image && Storage::disk('public')->exists($activity->image)) {
                Storage::disk('public')->delete($activity->image);
            }
            $validated['image'] = $request->file('image')->store('activities', 'public');
        }

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

    /**
     * تخزين أو تحديث تقييم الطالب للنشاط
     */
    public function rate(Request $request, $id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول لتقييم النشاط');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500'
        ], [
            'rating.required' => 'يرجى اختيار عدد النجوم',
            'rating.min' => 'أقل تقييم هو نجمة واحدة',
            'rating.max' => 'أقصى تقييم هو 5 نجوم',
            'review.max' => 'التعليق لا يتجاوز 500 حرف'
        ]);

        $activity = Activity::findOrFail($id);

        // ✅ التحقق من حالة النشاط (مفتوح أو مكتمل)
        if (!in_array($activity->status, ['مفتوح', 'مكتمل', 'active', 'completed'])) {
            return back()->with('error', 'لا يمكن تقييم هذا النشاط حالياً');
        }

        // استخدام updateOrCreate لمنع التكرار
        $rating = Rating::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'activity_id' => $activity->id
            ],
            [
                'rating' => $validated['rating'],
                'review' => $validated['review'] ?? null
            ]
        );

        return redirect()->back()->with('success', '🌟 شكرًا! تم حفظ تقييمك بنجاح');
    }

    /**
     * عرض الأنشطة للوحة تحكم المدير
     */
    public function adminIndex(Request $request)
    {
        $query = Activity::with(['activityType', 'users', 'ratings', 'creator']);
        
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $activities = $query->latest()->paginate(15);
        $activityTypes = ActivityType::all();
        
        return view('admin.activities.index', compact('activities', 'activityTypes'));
    }
}