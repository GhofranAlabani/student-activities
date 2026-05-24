<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityReport extends Model
{
    protected $fillable = [
        'activity_id',
        'submitted_by',
        'summary',
        'participants_count',
        'outcomes',
        'submitted_at',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
}
