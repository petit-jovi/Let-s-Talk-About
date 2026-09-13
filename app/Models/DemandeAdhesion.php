<?php

namespace App\Models;

use App\Enums\DemandeAdhesionStatut;
use App\Enums\MembreType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Classe "DemandeAdhesion" du diagramme de classes.
 * Operations soumettre() / approuver() / rejeter() portees par
 * App\Services\MembershipService pour garder le modele fin et testable.
 */
class DemandeAdhesion extends Model
{
    protected $table = 'demande_adhesions';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'date_naissance',
        'adresse',
        'profession',
        'type_membre_souhaite',
        'domaines_interet',
        'motivation',
        'comment_connu_lta',
        'accepte_statuts',
        'accepte_reglement_interieur',
        'consentement_traitement_donnees',
        'statut',
        'traite_par_id',
        'traite_le',
        'motif_refus',
        'notes_internes',
        'ip_soumission',
    ];

    protected function casts(): array
    {
        return [
            'type_membre_souhaite' => MembreType::class,
            'statut' => DemandeAdhesionStatut::class,
            'domaines_interet' => 'array',
            'date_naissance' => 'date',
            'traite_le' => 'datetime',
            'accepte_statuts' => 'boolean',
            'accepte_reglement_interieur' => 'boolean',
            'consentement_traitement_donnees' => 'boolean',
            // Donnees personnelles sensibles chiffrees au repos.
            'telephone' => 'encrypted',
            'adresse' => 'encrypted',
        ];
    }

    public function traitePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'traite_par_id');
    }

    public function memberProfile(): HasOne
    {
        return $this->hasOne(MemberProfile::class);
    }

    public function nomComplet(): string
    {
        return trim("{$this->prenom} {$this->nom}");
    }

    public function estEnAttente(): bool
    {
        return $this->statut === DemandeAdhesionStatut::EnAttente;
    }
}
