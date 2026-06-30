<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ✅ دوال مساعدة للتحقق من الصلاحيات
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    // ✅ علاقة المشرف مع Staff
    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    // ✅ علاقة المشرف مع الأنشطة (عبر جدول staff)
    public function supervisedActivities()
    {
        return $this->hasMany(Activity::class, 'supervisor_id');
    }

    // ✅ علاقات الطالب
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'registrations', 'student_id', 'activity_id');
    }

    public function favorites()
    {
        return $this->belongsToMany(Activity::class, 'favorites');
    }

    // ✅ علاقة مع التسجيلات (للطالب)
public function registrations(): \Illuminate\Database\Eloquent\Relations\HasMany
{
    return $this->hasMany(\App\Models\Registration::class, 'student_id');
}
}