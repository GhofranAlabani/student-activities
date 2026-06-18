<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'activity_id',
        'icon',
        'is_read',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public static function notify($userId, $type, $title, $message, $activityId = null, $icon = 'bell')
    {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'activity_id' => $activityId,
            'icon' => $icon,
            'is_read' => false,
        ]);
    }
}