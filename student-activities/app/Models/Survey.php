<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $fillable = ['activity_id', 'title', 'description', 'is_active'];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class)->orderBy('order');
    }

    public function answers()
    {
        return $this->hasMany(SurveyAnswers::class);
    }

    // التحقق إذا كان الطالب قد أجاب على الاستبيان
    public function hasUserAnswers($userId)
    {
        return $this->answers()->where('user_id', $userId)->exists();
    }
}