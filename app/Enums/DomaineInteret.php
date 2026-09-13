<?php

namespace App\Enums;

/**
 * Domaines de la culture pop couverts par l'objet social de LTA
 * (Statuts, Article 3 - Objet). Utilise dans le formulaire de demande
 * d'adhesion pour orienter le nouveau membre vers les bons projets.
 */
enum DomaineInteret: string
{
    case Musique = 'musique';
    case Cinema = 'cinema';
    case JeuxVideo = 'jeux_video';
    case Mode = 'mode';
    case Art = 'art';
    case BandeDessinee = 'bande_dessinee';
    case Animation = 'animation';
    case MediasSociaux = 'medias_sociaux';
    case Autre = 'autre';

    public function label(): string
    {
        return match ($this) {
            self::Musique => 'Musique',
            self::Cinema => 'Cinéma',
            self::JeuxVideo => 'Jeux vidéo',
            self::Mode => 'Mode',
            self::Art => 'Art',
            self::BandeDessinee => 'Bande dessinée',
            self::Animation => 'Animation',
            self::MediasSociaux => 'Médias sociaux & création numérique',
            self::Autre => 'Autre',
        };
    }

    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
