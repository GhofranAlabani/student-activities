<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{HasMany, BelongsTo, BelongsToMany, HasOne};

class Activity extends Model
{
    use SoftDeletes;

    // ✅ الحقول القابلة للتعديل (Mass Assignable)
    protected $fillable = [
        'title',
        'image',
        'description',
        'type_id',
        'supervisor_id',
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
        'extra_data', // ✅ أضفنا هذا الحقل لدعم البيانات الإضافية (JSON)
    ];

    // ✅ الحقول التي يجب تحويلها (Casts)
    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'max_participants' => 'integer',
        'points' => 'integer',
        'extra_data' => 'array', // ✅ هذا السطر مهم: يحول JSON إلى Array تلقائياً
    ];

    // ============================================
    // 🔗 العلاقات (Relationships)
    // ============================================

    // ✅ علاقة مع نوع النشاط
    public function activityType(): BelongsTo
    {
        return $this->belongsTo(ActivityType::class, 'type_id');
    }

    // ✅ علاقة مع المشرف
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    // ✅ علاقة مع الطلاب المسجلين
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'registrations', 'activity_id', 'student_id');
    }

    // ✅ علاقة مع نوع النشاط (بديل)
    public function type(): BelongsTo
    {
        return $this->belongsTo(ActivityType::class, 'type_id');
    }

    // ✅ علاقة مع المستخدم الذي أنشأ النشاط
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ✅ علاقة مع التسجيلات
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    // ✅ علاقة مع الحضور
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    // ✅ علاقة مع الوسوم
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'activity_tags');
    }

    // ✅ علاقة مع التقرير
    public function report(): HasOne
    {
        return $this->hasOne(ActivityReport::class);
    }

    // ✅ علاقة مع التقييمات
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    // ============================================
    // ⭐ Accessors & Helpers
    // ============================================

    public function getAverageRatingAttribute()
    {
        if ($this->relationLoaded('ratings')) {
            return $this->ratings->avg('rating') ?? 0;
        }
        return $this->ratings()->avg('rating') ?? 0;
    }

    public function getRatingsCountAttribute()
    {
        if ($this->relationLoaded('ratings')) {
            return $this->ratings->count();
        }
        return $this->ratings()->count();
    }

    public function isRatedByUser($userId)
    {
        if ($this->relationLoaded('ratings')) {
            return $this->ratings->contains('user_id', $userId);
        }
        return $this->ratings()->where('user_id', $userId)->exists();
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return 'https://via.placeholder.com/400x300?text=No+Image';
    }

    public function isAvailableForRegistration()
    {
        return $this->status === 'active' 
            && $this->date->isFuture()
            && ($this->max_participants === null || $this->users()->count() < $this->max_participants);
    }

    // ============================================
    // 🆕 Helpers للبيانات الإضافية (extra_data)
    // ============================================

    /**
     * جلب قيمة محددة من البيانات الإضافية
     * مثال: $activity->getExtra('speaker_name')
     */
    public function getExtra($key, $default = null)
    {
        return $this->extra_data[$key] ?? $default;
    }

    /**
     * التحقق إذا كانت البيانات الإضافية تحتوي على مفتاح معين
     */
    public function hasExtra($key): bool
    {
        return isset($this->extra_data[$key]);
    }

    /**
     * جلب كل البيانات الإضافية
     */
    public function getAllExtra(): array
    {
        return $this->extra_data ?? [];
    }
}