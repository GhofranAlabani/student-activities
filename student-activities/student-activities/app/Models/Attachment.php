<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'activity_id',
        'file_name',
        'file_path',
        'file_type',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}