<?php

namespace App\Mail;

use App\Models\DemandeAdhesion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Notification (ajout au-dela du strict diagramme, par courtoisie envers le
 * demandeur) informant du refus de la demande. Conformement aux Statuts
 * Art. 6(2), AUCUN motif n'est communique : le Bureau Executif "n'aura pas
 * à en faire connaître les raisons".
 */
class MembershipRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly DemandeAdhesion $demande)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Votre demande d'adhésion à LET'S TALK ABOUT",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.membership-rejected',
            with: ['demande' => $this->demande],
        );
    }
}
