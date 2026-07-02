<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Activity;
use App\Models\User;

class ActivityUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $activity;
    public $changes;

    public function __construct(User $user, Activity $activity, array $changes)
    {
        $this->user = $user;
        $this->activity = $activity;
        $this->changes = $changes;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📢 تم تحديث نشاط: ' . $this->activity->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.activity-updated',
        );
    }
}