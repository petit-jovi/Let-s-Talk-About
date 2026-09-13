<?php

namespace App\Enums;

/**
 * Types de membres - Statuts LTA, Article 6(1).
 */
enum MembreType: string
{
    case Fondateur = 'fondateur';
    case Actif = 'actif';
    case Sympathisant = 'sympathisant';
    case Honneur = 'honneur';

    public function label(): string
    {
        return match ($this) {
            self::Fondateur => 'Membre fondateur',
            self::Actif => 'Membre actif',
            self::Sympathisant => 'Membre sympathisant',
            self::Honneur => "Membre d'honneur",
        };
    }

    /**
     * Droit de vote et eligibilite aux instances dirigeantes (Art. 6).
     */
    public function droitDeVote(): bool
    {
        return match ($this) {
            self::Fondateur, self::Actif => true,
            self::Sympathisant, self::Honneur => false,
        };
    }

    /**
     * Types ouverts a la voie "demande d'adhesion" du site (Art. 6(2)).
     * Le statut Fondateur et Honneur ne s'obtiennent pas via ce formulaire.
     */
    public static function ouvertsALaDemande(): array
    {
        return [self::Actif, self::Sympathisant];
    }

    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
