<?php

namespace App\Support;

/**
 * Catalogue des permissions Spatie utilisees par LTA.
 *
 * Chaque constante correspond a un cas d'utilisation du diagramme de cas
 * d'utilisation (LTA-UsesCasesDiagrams.mdj). Voir README.md > "Matrice de
 * permissions" pour la repartition role -> permissions, et
 * database/seeders/RolePermissionSeeder.php pour son application.
 */
final class Permissions
{
    // --- Adhesions (module livre dans cette iteration) ---
    public const DEMANDES_CONSULTER = 'demandes.consulter';

    public const DEMANDES_VALIDER = 'demandes.valider';

    public const DEMANDES_REFUSER = 'demandes.refuser';

    public const MEMBRES_GERER = 'membres.gerer';

    // --- Gouvernance / bureau (prevues pour les prochaines iterations) ---
    public const ROLES_GERER = 'roles.gerer';

    public const FINANCES_SUIVRE = 'finances.suivre';

    public const BENEVOLES_COORDONNER = 'benevoles.coordonner';

    public const CONTENU_PUBLIER = 'contenu.publier';

    public const EVENEMENTS_GERER = 'evenements.gerer';

    public const PARTENARIATS_GERER = 'partenariats.gerer';

    public const AG_CONVOQUER = 'ag.convoquer';

    public const AG_PUBLIER_DOCUMENTS = 'ag.publier_documents';

    // --- Espace membre (prevues pour les prochaines iterations) ---
    public const PROFIL_GERER = 'profil.gerer';

    public const COTISATION_PAYER = 'cotisation.payer';

    public const EVENEMENT_SINSCRIRE = 'evenement.sinscrire';

    public const CONTENU_EXCLUSIF_CONSULTER = 'contenu_exclusif.consulter';

    public const DOCUMENTS_AG_TELECHARGER = 'documents_ag.telecharger';

    public const VOTES_PARTICIPER = 'votes.participer';

    public static function all(): array
    {
        return [
            self::DEMANDES_CONSULTER,
            self::DEMANDES_VALIDER,
            self::DEMANDES_REFUSER,
            self::MEMBRES_GERER,
            self::ROLES_GERER,
            self::FINANCES_SUIVRE,
            self::BENEVOLES_COORDONNER,
            self::CONTENU_PUBLIER,
            self::EVENEMENTS_GERER,
            self::PARTENARIATS_GERER,
            self::AG_CONVOQUER,
            self::AG_PUBLIER_DOCUMENTS,
            self::PROFIL_GERER,
            self::COTISATION_PAYER,
            self::EVENEMENT_SINSCRIRE,
            self::CONTENU_EXCLUSIF_CONSULTER,
            self::DOCUMENTS_AG_TELECHARGER,
            self::VOTES_PARTICIPER,
        ];
    }

    /**
     * Permissions communes a tout membre du Bureau Executif, quel que soit
     * son poste : le diagramme de cas d'utilisation modelise un acteur
     * unique "Bureau Executif (Admin)" pour la gestion des adhesions - la
     * decision de validation est collegiale (Statuts Art. 6(2) : "accepte
     * par le bureau executif").
     */
    public static function basesAdmin(): array
    {
        return [
            self::DEMANDES_CONSULTER,
            self::DEMANDES_VALIDER,
            self::DEMANDES_REFUSER,
            self::MEMBRES_GERER,
        ];
    }

    /**
     * Permissions supplementaires par poste du bureau (Statuts Art. 8/11).
     * Fusionnees avec basesAdmin() pour chaque role.
     */
    public static function matricePosteBureau(): array
    {
        return [
            'president' => [
                self::ROLES_GERER,
                self::FINANCES_SUIVRE,
                self::EVENEMENTS_GERER,
                self::CONTENU_PUBLIER,
                self::PARTENARIATS_GERER,
                self::AG_CONVOQUER,
                self::AG_PUBLIER_DOCUMENTS,
                self::BENEVOLES_COORDONNER,
            ],
            'secretaire_general' => [
                self::AG_CONVOQUER,
                self::AG_PUBLIER_DOCUMENTS,
            ],
            'tresorier' => [
                self::FINANCES_SUIVRE,
            ],
            'responsable_rh' => [
                self::BENEVOLES_COORDONNER,
            ],
            'charge_communication' => [
                self::CONTENU_PUBLIER,
            ],
            'responsable_activites_culturelles' => [
                self::EVENEMENTS_GERER,
            ],
            'charge_creation_contenu' => [
                self::CONTENU_PUBLIER,
            ],
            'responsable_partenariats' => [
                self::PARTENARIATS_GERER,
            ],
        ];
    }

    /**
     * Permissions accordees a tout compte Membre.
     */
    public static function basesMembre(): array
    {
        return [
            self::PROFIL_GERER,
            self::COTISATION_PAYER,
            self::EVENEMENT_SINSCRIRE,
            self::CONTENU_EXCLUSIF_CONSULTER,
            self::DOCUMENTS_AG_TELECHARGER,
            self::VOTES_PARTICIPER,
        ];
    }
}
