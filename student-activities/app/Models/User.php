<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'points',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ========================
    // العلاقات (Relationships)
    // ========================

    /**
     * الأنشطة التي سجل فيها الطالب (الجدول الجديد: activity_student)
     */
    public function activities()
{
    return $this->belongsToMany(
        Activity::class, 
        'activity_student', 
        'user_id',        // ← ✅ هذا التغيير المهم (كان student_id)
        'activity_id'
    )
    ->withPivot('status', 'points_earned', 'participated_at')
    ->withTimestamps();
}

    /** الأنشطة المفضلة */
    public function favorites()
    {
        return $this->belongsToMany(Activity::class, 'favorites', 'user_id', 'activity_id')
                    ->withTimestamps();
    }

    /** التقارير المرسلة */
    public function reports()
    {
        return $this->hasMany(ActivityReport::class);
    }
}