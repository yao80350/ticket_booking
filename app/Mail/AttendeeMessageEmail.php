<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AttendeeMessageEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $attendeeMessage;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($attendeeMessage)
    {
        $this->attendeeMessage = $attendeeMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->text('emails.attendee-message-email')
            ->subject($this->attendeeMessage->subject)
            ->from(config("mail.username"))->CC(config("mail.username"));
    }
}
