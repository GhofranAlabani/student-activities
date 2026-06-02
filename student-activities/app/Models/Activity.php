<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;

    // ✅ تم إضافة 'image' إلى القائمة
    protected $fillable = [
        'title',
        'image',        // ✅ حقل الصورة الجديد
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

    // ✅ العلاقة المطلوبة التي كانت مفقودة وتسبب الخطأ
    public function activityType()
    {
        return $this->belongsTo(ActivityType::class, 'type_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'registrations', 'activity_id', 'student_id');
    }

    // (اختياري) يمكنك الاحتفاظ بها كاسم بديل أو حذفها لعدم التكرار
    public function type()
    {
        return $this->belongsTo(ActivityType::class, 'type_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'activity_tags');
    }

    public function report()
    {
        return $this->hasOne(ActivityReport::class);
    }

    // ✅ دالة مساعدة لعرض مسار الصورة (اختياري لكن مفيد)
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('uploads/activities/' . $this->image);
        }
        // صورة افتراضية إذا ما فيش صورة
        return 'https://via.placeholder.com/400x300?text=No+Image';
    }
}