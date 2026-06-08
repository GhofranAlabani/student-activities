<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    protected $fillable = ['survey_id', 'question', 'type', 'options', 'required', 'order'];

    protected $casts = [
        'options' => 'array',
        'required' => 'boolean',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
}