<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{HasMany, BelongsTo, BelongsToMany, HasOne};

class Activity extends Model
{
    use SoftDeletes;

    // ✅ الحقول القابلة للتعديل (Mass Assignment)
    protected $fillable = [
        'title',
        'image',
        'description',
        'type_id',
        'location',
        'date',
        'time',
        'end_time',
        'max_participants',
        'points',
        'created_by',
        'status',
        'priority',
        'certificate',
        'online_link',
    ];

    // ✅ الحقول التي يجب تحويلها لنوع معين تلقائياً
    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'max_participants' => 'integer',
        'points' => 'integer',
    ];

    // ============================================
    // 🔗 العلاقات (Relationships)
    // ============================================

    public function activityType(): BelongsTo
    {
        return $this->belongsTo(ActivityType::class, 'type_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'registrations', 'activity_id', 'student_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(ActivityType::class, 'type_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'activity_tags');
    }

    public function report(): HasOne
    {
        return $this->hasOne(ActivityReport::class);
    }

    // ⭐⭐⭐ جديد: علاقة مع التقييمات ⭐⭐⭐
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    // ============================================
    // ⭐ Accessors & Helpers (دوال مساعدة)
    // ============================================

    /**
     * ✅ حساب متوسط التقييم تلقائياً (يظهر كـ $activity->average_rating)
     */
    public function getAverageRatingAttribute()
    {
        // إذا كان المتوسط محسوب مسبقاً عبر withAvg نستخدمه، وإلا نحسبه
        if ($this->relationLoaded('ratings')) {
            return $this->ratings->avg('rating') ?? 0;
        }
        return $this->ratings()->avg('rating') ?? 0;
    }

    /**
     * ✅ عدد التقييمات (يظهر كـ $activity->ratings_count)
     */
    public function getRatingsCountAttribute()
    {
        if ($this->relationLoaded('ratings')) {
            return $this->ratings->count();
        }
        return $this->ratings()->count();
    }

    /**
     * ✅ التحقق: هل هذا الطالب قيّم النشاط من قبل؟
     * الاستخدام: $activity->isRatedByUser(auth()->id())
     */
    public function isRatedByUser($userId)
    {
        // نبحث في التقييمات المحملة مسبقاً إن وجدت (لتحسين الأداء)
        if ($this->relationLoaded('ratings')) {
            return $this->ratings->contains('user_id', $userId);
        }
        // أو استعلام مباشر للداتابيز
        return $this->ratings()->where('user_id', $userId)->exists();
    }

    /**
     * ✅ دالة مساعدة لعرض مسار الصورة
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // تأكد أن المسار يتوافق مع إعدادات التخزين عندك
            return asset('storage/' . $this->image);
        }
        return 'https://via.placeholder.com/400x300?text=No+Image';
    }

    /**
     * ✅ دالة مساعدة: هل النشاط متاح للتسجيل؟
     */
    public function isAvailableForRegistration()
    {
        return $this->status === 'active' 
            && $this->date->isFuture()
            && ($this->max_participants === null || $this->users()->count() < $this->max_participants);
    }
}