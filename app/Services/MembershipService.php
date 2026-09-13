<?php

namespace App\Services;

use App\Enums\DemandeAdhesionStatut;
use App\Enums\MembreStatut;
use App\Enums\UserType;
use App\Mail\MembershipApprovedMail;
use App\Mail\MembershipRejectedMail;
use App\Mail\NewMembershipRequestMail;
use App\Models\DemandeAdhesion;
use App\Models\MemberProfile;
use App\Models\MemberStatusHistory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

/**
 * Porte les operations soumettre() / approuver() / rejeter() de la classe
 * DemandeAdhesion (Class Diagram), orchestrees selon
 * LTA-SequenceDiagram-Soumission&Validation_Adhesion.mdj.
 */
class MembershipService
{
    /**
     * Etape 2-6 du diagramme de sequence : enregistre la demande (statut
     * "En attente") puis notifie le Bureau Executif par email.
     */
    public function soumettre(array $donnees, ?string $ip): DemandeAdhesion
    {
        $demande = DemandeAdhesion::query()->create([
            ...$donnees,
            'statut' => DemandeAdhesionStatut::EnAttente,
            'ip_soumission' => $ip,
        ]);

        $this->notifierBureau($demande);

        return $demande;
    }

    /**
     * Etape 12-16 : validation par le Bureau Executif -> creation
     * automatique du compte Membre (statut Actif) -> email de bienvenue
     * contenant le lien d'activation (definition du mot de passe).
     */
    public function approuver(DemandeAdhesion $demande, User $traitePar): User
    {
        if (! $demande->estEnAttente()) {
            throw new \RuntimeException('Cette demande a déjà été traitée.');
        }

        return DB::transaction(function () use ($demande, $traitePar) {
            $user = User::query()->create([
                'nom' => $demande->nom,
                'prenom' => $demande->prenom,
                'email' => $demande->email,
                'type' => UserType::Membre,
                'password' => null,
            ]);

            // Numero d'adherent LTA sequentiel (LTA-005, LTA-006...).
            $user->attribuerNumeroLta();

            $user->assignRole('membre');

            $profile = MemberProfile::query()->create([
                'user_id' => $user->id,
                'type_membre' => $demande->type_membre_souhaite,
                'statut' => MembreStatut::Actif,
                'statut_change_le' => now(),
                'statut_change_par_id' => $traitePar->id,
                'date_adhesion' => now()->toDateString(),
                'telephone' => $demande->telephone,
                'adresse' => $demande->adresse,
                'date_naissance' => $demande->date_naissance,
                'profession' => $demande->profession,
                'domaines_interet' => $demande->domaines_interet,
                'demande_adhesion_id' => $demande->id,
            ]);

            // Entree d'historique : EnAttente (DemandeAdhesion) -> Actif (Membre).
            MemberStatusHistory::create([
                'member_profile_id' => $profile->id,
                'ancien_statut' => null,
                'nouveau_statut' => MembreStatut::Actif,
                'motif' => 'Validation de la demande d\'adhésion par le Bureau Exécutif.',
                'change_par_id' => $traitePar->id,
            ]);

            $demande->update([
                'statut' => DemandeAdhesionStatut::Approuvee,
                'traite_par_id' => $traitePar->id,
                'traite_le' => now(),
            ]);

            // Lien signe (valide 3 jours, cf config/auth.php "expire") de
            // definition du mot de passe - jamais de mot de passe en clair
            // envoye par email.
            $token = Password::broker()->createToken($user);

            Mail::to($user->email)->send(new MembershipApprovedMail($user, $token));

            return $user;
        });
    }

    /**
     * Refus de la demande. Conformement aux Statuts Art. 6(2), le Bureau
     * Executif n'a pas a motiver son refus aupres du demandeur : `motif`
     * reste un champ interne (notes_internes), jamais transmis par email.
     */
    public function rejeter(DemandeAdhesion $demande, User $traitePar, ?string $motifInterne = null): DemandeAdhesion
    {
        if (! $demande->estEnAttente()) {
            throw new \RuntimeException('Cette demande a déjà été traitée.');
        }

        $demande->update([
            'statut' => DemandeAdhesionStatut::Rejetee,
            'traite_par_id' => $traitePar->id,
            'traite_le' => now(),
            'motif_refus' => $motifInterne,
        ]);

        Mail::to($demande->email)->send(new MembershipRejectedMail($demande));

        return $demande;
    }

    protected function notifierBureau(DemandeAdhesion $demande): void
    {
        $destinataires = array_merge(
            config('mail.lta_bureau_notification_emails', []),
            User::query()->where('type', UserType::Admin)->pluck('email')->all()
        );

        $destinataires = array_values(array_unique(array_filter($destinataires)));

        if (empty($destinataires)) {
            Log::warning('Aucun destinataire configure pour la notification de nouvelle demande d\'adhesion.', [
                'demande_id' => $demande->id,
            ]);

            return;
        }

        Mail::to($destinataires)->send(new NewMembershipRequestMail($demande));
    }
}
