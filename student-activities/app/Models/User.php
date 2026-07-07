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
         'total_points',      
         'level',               
         'activities_completed',
         'current_streak',      
         'longest_streak',      
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
    return $this->belongsToMany(Activity::class, 'registrations', 'student_id', 'activity_id')
                ->withPivot('id', 'created_at')
                ->withTimestamps();
} 


/**
 * حساب النقاط الإجمالية من الحضور الفعلي فقط
 */
public function getEarnedPointsAttribute()
{
    return $this->attendanceRecords()
        ->where('status', 'present')
        ->sum('points_earned');
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

public function attendanceRecords()
{
    return $this->hasMany(AttendanceRecord::class);
}

// العلاقة مع الشارات
public function badges()
{
    return $this->belongsToMany(Badge::class, 'user_badges')
                ->withPivot('earned_at')
                ->withTimestamps();
}

// العلاقة مع سجل النقاط
public function pointsHistory()
{
    return $this->hasMany(PointsHistory::class);
}


// حساب المستوى تلقائياً
public function getLevelNameAttribute()
{
    $points = $this->total_points ?? 0;
    
    if ($points >= 500) return ['name' => 'خبير', 'icon' => '👑', 'color' => '#9333ea'];
    if ($points >= 200) return ['name' => 'متقدم', 'icon' => '⭐', 'color' => '#3b82f6'];
    if ($points >= 100) return ['name' => 'متوسط', 'icon' => '🌟', 'color' => '#10b981'];
    if ($points >= 50)  return ['name' => 'نشيط', 'icon' => '🔥', 'color' => '#f59e0b'];
    return ['name' => 'مبتدئ', 'icon' => '🌱', 'color' => '#6b7280'];
}

// النقاط اللازمة للمستوى التالي
public function getNextLevelPointsAttribute()
{
    $points = $this->total_points ?? 0;
    
    if ($points >= 500) return 500;
    if ($points >= 200) return 500;
    if ($points >= 100) return 200;
    if ($points >= 50)  return 100;
    return 50;
}

// نسبة التقدم للمستوى التالي
public function getLevelProgressAttribute()
{
    $points = $this->total_points ?? 0;
    $nextLevel = $this->next_level_points;
    
    if ($points >= 500) return 100;
    
    $currentLevelStart = 0;
    if ($points >= 200) $currentLevelStart = 200;
    elseif ($points >= 100) $currentLevelStart = 100;
    elseif ($points >= 50) $currentLevelStart = 50;
    
    $progress = (($points - $currentLevelStart) / ($nextLevel - $currentLevelStart)) * 100;
    return min(100, max(0, $progress));
}
}