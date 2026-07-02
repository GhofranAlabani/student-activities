<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Mail\ActivityUpdatedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
    public function create()
    {
        $activityTypes = ActivityType::all();
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

        if (isset($validated['activity_type_id'])) {
            $validated['type_id'] = $validated['activity_type_id'];
            unset($validated['activity_type_id']);
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('activities', 'public');
        }

        if (auth()->check() && Schema::hasColumn('activities', 'created_by')) {
            $validated['created_by'] = auth()->id();
        }

        Activity::create($validated);

        return redirect()->route('activities.index')
            ->with('success', '✅ تمت إضافة النشاط بنجاح');
    }

    /**
     * عرض تفاصيل نشاط مع التقييمات
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
        
        $userRating = null;
        if (auth()->check()) {
            $userRating = $activity->ratings->firstWhere('user_id', auth()->id());
        }
        
        return view('activities.show', compact('activity', 'userRating'));
    }

    /**
     * عرض نموذج تعديل نشاط
     */
    public function edit($id)
    {
        $activity = Activity::findOrFail($id);
        $activityTypes = ActivityType::all();
        $supervisors = \App\Models\User::whereIn('role', ['admin', 'staff'])->get();
        
        return view('activities.edit', compact('activity', 'activityTypes', 'supervisors'));
    }

    /**
     * تحديث نشاط
     */
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

    if (isset($validated['activity_type_id'])) {
        $validated['type_id'] = $validated['activity_type_id'];
        unset($validated['activity_type_id']);
    }

    if ($request->hasFile('image')) {
        if ($activity->image && Storage::disk('public')->exists($activity->image)) {
            Storage::disk('public')->delete($activity->image);
        }
        $validated['image'] = $request->file('image')->store('activities', 'public');
    }

    // ✅ تتبع التغييرات المهمة قبل التحديث
    $changes = [];
    
    if (isset($validated['location']) && $activity->location !== $validated['location']) {
        $changes['المكان'] = $validated['location'];
    }
    
    if (isset($validated['date']) && $activity->date !== $validated['date']) {
        $changes['التاريخ'] = \Carbon\Carbon::parse($validated['date'])->format('Y/m/d');
    }
    
    if (isset($validated['time']) && $activity->time !== $validated['time']) {
        $changes['الوقت'] = $validated['time'];
    }
    
    if (isset($validated['status']) && $activity->status !== $validated['status']) {
        $changes['الحالة'] = $validated['status'];
    }

    // تحديث النشاط
    $activity->update($validated);

    // ✅ إرسال إشعار لكل الطلاب المسجلين إذا فيه تغييرات مهمة
    if (!empty($changes)) {
        // جلب كل الطلاب المسجلين في هذا النشاط
        $registrations = \DB::table('registrations')
            ->where('activity_id', $activity->id)
            ->pluck('student_id');

        $users = \App\Models\User::whereIn('id', $registrations)->get();

        // إرسال إيميل لكل طالب
        foreach ($users as $user) {
            if ($user->email) {
                try {
                    Mail::to($user->email)->send(new ActivityUpdatedMail($user, $activity, $changes));
                } catch (\Exception $e) {
                    Log::error('Failed to send email to: ' . $user->email . ' - ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('activities.index')
            ->with('success', "✅ تم تعديل النشاط بنجاح وإرسال إشعارات لـ {$users->count()} طالب مسجل");
    }

    return redirect()->route('activities.index')
        ->with('success', '✅ تم تعديل النشاط بنجاح');
}
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

        if (!in_array($activity->status, ['مفتوح', 'مكتمل', 'active', 'completed'])) {
            return back()->with('error', 'لا يمكن تقييم هذا النشاط حالياً');
        }

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

    /**
     * تصدير النشاط إلى Google Calendar
     */
    public function exportToCalendar(Activity $activity)
    {
        $user = Auth::user();
        
        // التحقق أن الطالب مسجل في النشاط
        $isRegistered = $activity->registrations()
            ->where('student_id', $user->id)
            ->exists();
        
        if (!$isRegistered) {
            return back()->with('error', 'يجب أن تكون مسجلاً في النشاط أولاً');
        }

        try {
            // استخراج التاريخ فقط (بدون الوقت) من حقل date
            $dateOnly = \Carbon\Carbon::parse($activity->date)->format('Y-m-d');
            
            // الوقت الافتراضي إذا ما كان موجود
            $startTime = $activity->time ?? '10:00';
            $endTime = $activity->end_time ?? $activity->time ?? '12:00';
            
            // بناء التواريخ بشكل صحيح
            $startDate = \Carbon\Carbon::parse($dateOnly . ' ' . $startTime)->format('Ymd\THis');
            $endDate = \Carbon\Carbon::parse($dateOnly . ' ' . $endTime)->format('Ymd\THis');
            
            // إذا وقت النهاية أصغر من البداية، نضيف ساعتين
            if ($endDate <= $startDate) {
                $endDate = \Carbon\Carbon::parse($dateOnly . ' ' . $startTime)->addHours(2)->format('Ymd\THis');
            }
            
        } catch (\Exception $e) {
            // في حالة أي خطأ في التواريخ، نستخدم الوقت الحالي
            $startDate = now()->format('Ymd\THis');
            $endDate = now()->addHours(2)->format('Ymd\THis');
        }
        
        // إنشاء رابط Google Calendar
        $googleCalendarUrl = 'https://www.google.com/calendar/render?action=TEMPLATE'
            . '&text=' . urlencode($activity->title)
            . '&dates=' . $startDate . '/' . $endDate
            . '&details=' . urlencode($activity->description . "\n\nالموقع: " . ($activity->location ?? 'غير محدد'))
            . '&location=' . urlencode($activity->location ?? 'غير محدد')
            . '&sprop=website:' . urlencode(url('/'));

        return redirect($googleCalendarUrl);
    }
}