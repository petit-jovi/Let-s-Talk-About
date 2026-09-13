<?php

namespace App\Http\Requests\Admin;

use App\Enums\MembreStatut;
use App\Support\Permissions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Changement manuel de statut d'un membre par le Bureau
 * (LTA-Cycle de vie dun membre.mdj). La légalité de la transition
 * elle-même est vérifiée par MemberLifecycleService contre le diagramme
 * d'états.
 */
class UpdateMemberStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(Permissions::MEMBRES_GERER) ?? false;
    }

    public function rules(): array
    {
        return [
            'statut' => ['required', Rule::enum(MembreStatut::class)],
            'motif' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'statut' => 'nouveau statut',
            'motif' => 'motif',
        ];
    }
}
