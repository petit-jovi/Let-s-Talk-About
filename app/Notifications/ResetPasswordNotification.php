<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

/**
 * Email "mot de passe oublié" (français), pointant vers la route
 * password.reset du site LTA. Expédié via l'adresse du Bureau
 * (config mail.from), comme tous les emails de l'association.
 */
class ResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(public string $token)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $minutes = config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60);

        return (new MailMessage)
            ->subject('Réinitialisation de votre mot de passe — LET\'S TALK ABOUT')
            ->greeting('Bonjour,')
            ->line('Vous recevez cet email car une réinitialisation de mot de passe a été demandée pour votre compte.')
            ->action('Réinitialiser le mot de passe', $url)
            ->line("Ce lien expire dans {$minutes} minutes.")
            ->line("Si vous n'êtes pas à l'origine de cette demande, aucune action n'est requise.")
            ->salutation('Le Bureau Exécutif de '.config('app.name'));
    }
}
