<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
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

    public function attendance()
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
}