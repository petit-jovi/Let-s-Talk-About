<?php

namespace App\Enums;

/**
 * Statut de la DemandeAdhesion.
 * Source : LTA-SequenceDiagram-Soumission&Validation_Adhesion.mdj
 *   message 3 : "Enregistrer DemandeAdhesion (Statut: En attente)"
 *   message 13/14 : "validerAdhesion(idDemande)" -> "Creer compte Membre (Statut: Actif)"
 *   (le refus correspond a l'operation rejeter() de la classe DemandeAdhesion)
 */
enum DemandeAdhesionStatut: string
{
    case EnAttente = 'en_attente';
    case Approuvee = 'approuvee';
    case Rejetee = 'rejetee';

    public function label(): string
    {
        return match ($this) {
            self::EnAttente => 'En attente',
            self::Approuvee => 'Approuvée',
            self::Rejetee => 'Refusée',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::EnAttente => 'amber',
            self::Approuvee => 'green',
            self::Rejetee => 'red',
        };
    }
}
