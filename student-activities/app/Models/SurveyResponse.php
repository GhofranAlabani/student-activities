<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    protected $fillable = [
        'user_id',
        'activity_id',
        'question_id',
        'answer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class);
    }

    public static function hasResponded($userId, $activityId)
    {
        return static::where('user_id', $userId)
            ->where('activity_id', $activityId)
            ->exists();
    }
}