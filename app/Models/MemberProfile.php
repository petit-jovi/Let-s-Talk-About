<?php

namespace App\Models;

use App\Enums\MembreStatut;
use App\Enums\MembreType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Specialisation "Membre" de la classe Utilisateur (Class Diagram).
 */
class MemberProfile extends Model
{
    protected $fillable = [
        'user_id',
        'type_membre',
        'statut',
        'statut_change_le',
        'statut_change_par_id',
        'statut_motif',
        'date_adhesion',
        'telephone',
        'adresse',
        'date_naissance',
        'profession',
        'domaines_interet',
        'demande_adhesion_id',
    ];

    protected function casts(): array
    {
        return [
            'type_membre' => MembreType::class,
            'statut' => MembreStatut::class,
            'statut_change_le' => 'datetime',
            'date_adhesion' => 'date',
            'date_naissance' => 'date',
            'domaines_interet' => 'array',
            // Chiffrement au repos (AES-256, cle APP_KEY) des donnees personnelles.
            'telephone' => 'encrypted',
            'adresse' => 'encrypted',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function demandeAdhesion(): BelongsTo
    {
        return $this->belongsTo(DemandeAdhesion::class);
    }

    public function statutChangePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'statut_change_par_id');
    }

    public function historiquesStatut(): HasMany
    {
        return $this->hasMany(MemberStatusHistory::class)->latest();
    }
}
