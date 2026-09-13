<?php

namespace App\Enums;

/**
 * Statut du cycle de vie d'un membre.
 * Source : LTA-Cycle de vie dun membre.mdj (diagramme d'etats-transitions).
 *
 *   [*] -> EnAttente          : Demande Adhesion
 *   EnAttente -> Actif        : Validation Bureau & Paiement cotisation
 *   EnAttente -> Rejete       : Refus du Bureau
 *   Actif -> EnRetard         : Non-paiement 4 mois apres echeance
 *   EnRetard -> Exclu         : Radiation pour non paiement
 *   Actif -> Suspendu         : Decision Bureau
 *   Suspendu -> Actif         : Reintegration
 *   Actif -> Demissionnaire   : Envoie Demission ecrite
 *   Suspendu -> Demissionnaire: Envoie Demission ecrite
 *   Exclu / Demissionnaire / Rejete -> [*]
 *
 * NB: EnAttente et Rejete concernent la DemandeAdhesion (avant creation du
 * compte Membre). Le compte utilisateur "Membre" n'existe qu'a partir du
 * statut Actif (creation automatique apres validation, cf. sequence
 * "Soumission & Validation Adhesion").
 *
 * EXTENSION assumee (hors diagramme, signalee comme le sont les autres
 * ecarts du projet) : EnRetard -> Actif, pour permettre au Tresorier de
 * regulariser un membre qui s'acquitte de sa cotisation en retard avant
 * radiation. Sans cette transition, une mise "en retard" erronee serait
 * irreversible.
 */
enum MembreStatut: string
{
    case Actif = 'actif';
    case EnRetard = 'en_retard';
    case Suspendu = 'suspendu';
    case Exclu = 'exclu';
    case Demissionnaire = 'demissionnaire';

    public function label(): string
    {
        return match ($this) {
            self::Actif => 'Actif',
            self::EnRetard => 'En retard de cotisation',
            self::Suspendu => 'Suspendu',
            self::Exclu => 'Exclu',
            self::Demissionnaire => 'Démissionnaire',
        };
    }

    /**
     * Un membre dans cet etat peut-il se connecter et utiliser son espace ?
     * Art. 7(2) : la suspension entraine la perte du droit de participer a
     * la vie sociale.
     */
    public function accesAutorise(): bool
    {
        return match ($this) {
            self::Actif, self::EnRetard => true,
            self::Suspendu, self::Exclu, self::Demissionnaire => false,
        };
    }

    /**
     * Etat terminal du cycle de vie (aucune transition sortante).
     */
    public function estFinal(): bool
    {
        return match ($this) {
            self::Exclu, self::Demissionnaire => true,
            default => false,
        };
    }

    /**
     * Statuts vers lesquels une transition manuelle est autorisee depuis
     * l'etat courant (diagramme d'etats-transitions + extension EnRetard->Actif).
     *
     * @return array<int, self>
     */
    public function transitionsAutorisees(): array
    {
        return match ($this) {
            self::Actif => [self::EnRetard, self::Suspendu, self::Demissionnaire],
            self::EnRetard => [self::Exclu, self::Actif],
            self::Suspendu => [self::Actif, self::Demissionnaire],
            self::Exclu, self::Demissionnaire => [],
        };
    }

    public function peutDevenir(self $cible): bool
    {
        return in_array($cible, $this->transitionsAutorisees(), true);
    }

    /**
     * Libelle de l'action metier correspondant a la transition, pour l'UI.
     */
    public function libelleTransitionVers(self $cible): string
    {
        return match ([$this, $cible]) {
            [self::Actif, self::EnRetard] => 'Marquer en retard de cotisation',
            [self::Actif, self::Suspendu] => 'Suspendre le membre',
            [self::Actif, self::Demissionnaire] => 'Enregistrer la démission',
            [self::EnRetard, self::Exclu] => 'Radier pour non-paiement',
            [self::EnRetard, self::Actif] => 'Régulariser (cotisation payée)',
            [self::Suspendu, self::Actif] => 'Réintégrer le membre',
            [self::Suspendu, self::Demissionnaire] => 'Enregistrer la démission',
            default => 'Passer au statut « '.$cible->label().' »',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Actif => 'green',
            self::EnRetard => 'amber',
            self::Suspendu => 'orange',
            self::Exclu => 'red',
            self::Demissionnaire => 'slate',
        };
    }

    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
