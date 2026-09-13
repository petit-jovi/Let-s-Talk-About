<?php

namespace App\Http\Requests;

use App\Enums\DemandeAdhesionStatut;
use App\Enums\DomaineInteret;
use App\Enums\MembreType;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validation du formulaire public "Demande d'adhesion".
 * Champs alignes sur DemandeAdhesion (Class Diagram) + les conditions
 * d'admission des Statuts LTA, Article 6(2).
 */
class StoreDemandeAdhesionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                // Un compte existe deja avec cet email (deja membre/admin).
                Rule::unique(User::class, 'email'),
                // Une demande est deja en attente pour cet email : evite les doublons.
                Rule::unique('demande_adhesions', 'email')
                    ->where('statut', DemandeAdhesionStatut::EnAttente->value),
            ],
            'telephone' => ['required', 'string', 'max:30'],
            'date_naissance' => ['nullable', 'date', 'before:-15 years'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'profession' => ['nullable', 'string', 'max:150'],

            'type_membre_souhaite' => [
                'required',
                Rule::in(array_map(fn (MembreType $t) => $t->value, MembreType::ouvertsALaDemande())),
            ],

            'domaines_interet' => ['nullable', 'array'],
            'domaines_interet.*' => [Rule::in(DomaineInteret::values())],

            'motivation' => ['required', 'string', 'min:20', 'max:2000'],
            'comment_connu_lta' => ['nullable', 'string', 'max:150'],

            // Statuts Art. 6(2) : conditions d'admission obligatoires.
            'accepte_statuts' => ['accepted'],
            'accepte_reglement_interieur' => ['accepted'],
            'consentement_traitement_donnees' => ['accepted'],

            // Piege a robots : doit toujours rester vide (champ masque en CSS).
            'site_web' => ['prohibited'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nom' => 'nom',
            'prenom' => 'prénom',
            'email' => 'adresse e-mail',
            'telephone' => 'téléphone',
            'date_naissance' => 'date de naissance',
            'type_membre_souhaite' => "type d'adhésion souhaité",
            'motivation' => 'motivation',
            'accepte_statuts' => 'acceptation des statuts',
            'accepte_reglement_interieur' => 'acceptation du règlement intérieur',
            'consentement_traitement_donnees' => 'consentement au traitement des données',
        ];
    }

    public function messages(): array
    {
        return [
            'date_naissance.before' => "Vous devez avoir au moins 15 ans pour soumettre une demande d'adhésion.",
            'accepted' => 'Vous devez accepter :attribute pour poursuivre.',
            'email.unique' => 'Un compte ou une demande en attente existe déjà avec cette adresse e-mail.',
        ];
    }

    /**
     * Donnees pretes a etre persistees (exclut le champ honeypot).
     */
    public function donneesValidees(): array
    {
        return collect($this->validated())
            ->except('site_web')
            ->all();
    }
}
