<?php

namespace App\Enums;

/**
 * Postes du Bureau Executif - Statuts LTA, Article 8.
 *
 * Les 4 premiers sont les postes du bureau fondateur (obligatoires).
 * Les 4 suivants sont les postes d'elargissement votables en Assemblee Generale.
 */
enum BureauRole: string
{
    case President = 'president';
    case SecretaireGeneral = 'secretaire_general';
    case Tresorier = 'tresorier';
    case ResponsableRH = 'responsable_rh';

    case ChargeCommunication = 'charge_communication';
    case ResponsableActivitesCulturelles = 'responsable_activites_culturelles';
    case ChargeCreationContenu = 'charge_creation_contenu';
    case ResponsablePartenariats = 'responsable_partenariats';

    public function label(): string
    {
        return match ($this) {
            self::President => 'Président',
            self::SecretaireGeneral => 'Secrétaire général',
            self::Tresorier => 'Trésorier',
            self::ResponsableRH => 'Responsable ressources humaines',
            self::ChargeCommunication => 'Chargé de la communication',
            self::ResponsableActivitesCulturelles => 'Responsable des activités culturelles',
            self::ChargeCreationContenu => 'Chargé de la création de contenu',
            self::ResponsablePartenariats => 'Responsable partenariats & sponsoring',
        };
    }

    /**
     * Nom du role Spatie correspondant (utilise pour la matrice de permissions).
     */
    public function spatieRole(): string
    {
        return $this->value;
    }

    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
