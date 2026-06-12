<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    protected $fillable = [
        'question',
        'type',
        'options',
        'is_required',
        'order',
        'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function responses()
    {
        return $this->hasMany(SurveyResponse::class, 'question_id');
    }
}