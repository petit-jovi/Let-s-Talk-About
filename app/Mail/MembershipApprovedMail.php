<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Message 16 du diagramme de sequence : "Envoyer email de bienvenue au Visiteur".
 * Contient le lien signe permettant de definir le mot de passe du compte
 * cree automatiquement (aucun mot de passe n'est jamais transmis en clair).
 */
class MembershipApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public readonly string $activationUrl;

    public function __construct(public readonly User $user, string $token)
    {
        $this->activationUrl = route('password.activate', [
            'token' => $token,
            'email' => $user->email,
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenue chez LET\'S TALK ABOUT — activez votre compte',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.membership-approved',
            with: [
                'user' => $this->user,
                'activationUrl' => $this->activationUrl,
            ],
        );
    }
}
