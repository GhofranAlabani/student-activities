<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
    ];

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_tags');
    }
}