<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswers extends Model
{
    protected $fillable = ['survey_id', 'user_id', 'activity_id', 'answers'];

    protected $casts = [
        'answers' => 'array',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}