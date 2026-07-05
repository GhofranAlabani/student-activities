<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointsHistory extends Model
{
    protected $table = 'points_history'; // ✅ هذا السطر يحل المشكلة!

    protected $fillable = [
        'user_id',
        'activity_id',
        'points',
        'reason',
    ];

    protected $casts = [
        'points' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}