<?php

namespace App\Models;

use App\Enums\BureauRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Specialisation "Administrateur" de la classe Utilisateur (Class Diagram).
 * Represente un membre du Bureau Executif (Statuts Art. 8).
 */
class AdminProfile extends Model
{
    protected $fillable = [
        'user_id',
        'role_bureau',
        'date_debut_mandat',
        'date_fin_mandat',
    ];

    protected function casts(): array
    {
        return [
            'role_bureau' => BureauRole::class,
            'date_debut_mandat' => 'date',
            'date_fin_mandat' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
