<?php

namespace App\Models;

use App\Enums\UserType;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * Classe "Utilisateur" (abstraite dans le diagramme de classes).
 * Specialisee via une relation 1-1 vers member_profiles ou admin_profiles
 * selon la valeur de `type`.
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected $fillable = [
        'numero_lta',
        'nom',
        'prenom',
        'email',
        'password',
        'type',
        'activated_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'activated_at' => 'datetime',
            'password' => 'hashed',
            'type' => UserType::class,
        ];
    }

    public function memberProfile(): HasOne
    {
        return $this->hasOne(MemberProfile::class);
    }

    public function adminProfile(): HasOne
    {
        return $this->hasOne(AdminProfile::class);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function nomComplet(): string
    {
        return trim("{$this->prenom} {$this->nom}");
    }

    /**
     * Identifiant d'adhérent affichable, ex. "LTA-001" (Class Diagram :
     * attribut idUtilisateur, décliné en numéro de membre lisible).
     */
    public function numeroLtaFormate(): ?string
    {
        return $this->numero_lta === null
            ? null
            : sprintf('LTA-%03d', $this->numero_lta);
    }

    /**
     * Prochain numéro d'adhérent disponible (séquence continue, jamais
     * réutilisée, y compris pour les comptes supprimés).
     */
    public static function prochainNumeroLta(): int
    {
        return (int) static::withTrashed()->max('numero_lta') + 1;
    }

    /**
     * Attribue le prochain numéro d'adhérent si le compte n'en a pas encore.
     */
    public function attribuerNumeroLta(): void
    {
        if ($this->numero_lta === null) {
            $this->forceFill(['numero_lta' => static::prochainNumeroLta()])->save();
        }
    }

    /**
     * "dateInscription" du diagramme de classes.
     */
    public function dateInscription(): ?\Illuminate\Support\Carbon
    {
        return $this->created_at;
    }

    public function isAdmin(): bool
    {
        return $this->type === UserType::Admin;
    }

    public function isMembre(): bool
    {
        return $this->type === UserType::Membre;
    }

    /**
     * Le compte a-t-il ete active (mot de passe defini par son titulaire) ?
     */
    public function estActive(): bool
    {
        return $this->activated_at !== null && $this->password !== null;
    }

    /**
     * Un membre suspendu/exclu/demissionnaire ne peut plus se connecter
     * (Statuts Art. 7). Les administrateurs n'ont pas ce cycle de vie.
     */
    public function accesAutorise(): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $this->memberProfile?->statut?->accesAutorise() ?? false;
    }
}
