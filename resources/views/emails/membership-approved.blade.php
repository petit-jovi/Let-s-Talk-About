<x-mail::message>
# Bienvenue chez LET'S TALK ABOUT !

Bonjour {{ $user->prenom }},

Votre demande d'adhésion a été **validée** par le Bureau Exécutif. Vous faites désormais officiellement partie de l'association **LET'S TALK ABOUT (LTA)**.

Pour finaliser la création de votre compte, définissez votre mot de passe en cliquant sur le bouton ci-dessous. Ce lien est personnel et expire dans 60 minutes.

<x-mail::button :url="$activationUrl">
Définir mon mot de passe
</x-mail::button>

Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :
{{ $activationUrl }}

Une fois votre compte activé, vous pourrez vous connecter à votre espace membre pour gérer votre profil, régler votre cotisation annuelle et suivre les activités de l'association.

À très bientôt,<br>
Le Bureau Exécutif de {{ config('app.name') }}
</x-mail::message>
