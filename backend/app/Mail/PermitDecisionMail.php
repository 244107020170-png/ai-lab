<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\LabPermitRequest;

class PermitDecisionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $permit;
    public $decision;

    /**
     * Create a new message instance.
     */
    public function __construct(LabPermitRequest $permit, string $decision)
    {
        $this->permit = $permit;
        $this->decision = $decision;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject("Your Lab Permit Request Has Been " . ucfirst($this->decision))
                    ->markdown('emails.permit_decision');
    }
}
