<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    // ✅ الحقول القابلة للتعديل (موحدة على 'review')
    protected $fillable = [
        'user_id',
        'activity_id',
        'rating',
        'review',  // ⚠️ انتبه: هنا 'review' وليس 'comment'
    ];

    /**
     * العلاقة مع الطالب الذي قام بالتقييم
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * العلاقة مع النشاط الذي تم تقييمه
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }
    
    /**
     * ✅ ميزة إضافية: تنسيق النجوم كـ 5.0 / 4.5 ...
     */
    public function getFormattedRatingAttribute()
    {
        return number_format($this->rating, 1);
    }
}