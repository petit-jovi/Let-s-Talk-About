<x-mail::message>
# Nouvelle demande d'adhésion

Une nouvelle demande d'adhésion vient d'être soumise sur le site de **LET'S TALK ABOUT**.

<x-mail::table>
| Champ | Valeur |
| :- | :- |
| Nom complet | {{ $demande->nomComplet() }} |
| Email | {{ $demande->email }} |
| Type d'adhésion souhaité | {{ $demande->type_membre_souhaite->label() }} |
| Date de la demande | {{ $demande->created_at->format('d/m/Y à H:i') }} |
</x-mail::table>

**Motivation :**

{{ $demande->motivation }}

<x-mail::button :url="route('admin.membership-requests.show', $demande)">
Consulter et traiter la demande
</x-mail::button>

Pour rappel (Statuts LTA, Article 6(2)), le Bureau Exécutif décide de l'admission des nouveaux membres et n'a pas à motiver un refus auprès du demandeur.

Cordialement,<br>
Le système {{ config('app.name') }}
</x-mail::message>
