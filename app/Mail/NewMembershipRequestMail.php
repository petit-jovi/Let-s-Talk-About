<?php

namespace App\Mail;

use App\Models\DemandeAdhesion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Message 6 du diagramme de sequence : "Notification email (Nouvelle demande)"
 * envoye au Bureau Executif.
 */
class NewMembershipRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly DemandeAdhesion $demande)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[LTA] Nouvelle demande d'adhésion — {$this->demande->nomComplet()}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-membership-request',
            with: ['demande' => $this->demande],
        );
    }
}
