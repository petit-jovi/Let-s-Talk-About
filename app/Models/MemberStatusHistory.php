<?php

namespace App\Models;

use App\Enums\MembreStatut;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Journal des transitions du cycle de vie d'un membre
 * (LTA-Cycle de vie dun membre.mdj). Une ligne par changement de statut.
 */
class MemberStatusHistory extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'member_profile_id',
        'ancien_statut',
        'nouveau_statut',
        'motif',
        'change_par_id',
    ];

    protected function casts(): array
    {
        return [
            'ancien_statut' => MembreStatut::class,
            'nouveau_statut' => MembreStatut::class,
        ];
    }

    public function memberProfile(): BelongsTo
    {
        return $this->belongsTo(MemberProfile::class);
    }

    public function changePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'change_par_id');
    }
}
