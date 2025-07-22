<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Household;

class HouseholdInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $household;
    public $inviteLink;

    /**
     * Create a new message instance.
     */
    public function __construct(Household $household, $inviteLink)
    {
        $this->household = $household;
        $this->inviteLink = $inviteLink;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('You are invited to join a household on ChoreBoard')
            ->markdown('emails.invite')
            ->with([
                'household' => $this->household,
                'inviteLink' => $this->inviteLink,
            ]);
    }
}
