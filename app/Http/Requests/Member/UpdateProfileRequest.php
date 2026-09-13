<?php

namespace App\Http\Requests\Member;

use App\Enums\DomaineInteret;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * "Mon profil" — édition (Class Diagram : Utilisateur::mettreAJourProfil()).
 * Les champs pilotés par le Bureau (type de membre, statut, date d'adhésion,
 * numéro LTA) ne sont volontairement pas éditables ici.
 */
class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $rules = [
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'email' => [
                'required', 'email:rfc', 'max:255',
                Rule::unique(User::class, 'email')->ignore($this->user()->id),
            ],
        ];

        // Champs spécifiques au profil Membre.
        if ($this->user()->memberProfile) {
            $rules += [
                'telephone' => ['required', 'string', 'max:30'],
                'adresse' => ['nullable', 'string', 'max:255'],
                'date_naissance' => ['nullable', 'date', 'before:-15 years'],
                'profession' => ['nullable', 'string', 'max:150'],
                'domaines_interet' => ['nullable', 'array'],
                'domaines_interet.*' => [Rule::in(DomaineInteret::values())],
            ];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'prenom' => 'prénom',
            'email' => 'adresse e-mail',
            'telephone' => 'téléphone',
            'date_naissance' => 'date de naissance',
        ];
    }
}
