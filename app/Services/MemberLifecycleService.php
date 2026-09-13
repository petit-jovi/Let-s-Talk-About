<?php

namespace App\Services;

use App\Enums\MembreStatut;
use App\Models\MemberProfile;
use App\Models\MemberStatusHistory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Gouverne les transitions du cycle de vie d'un membre
 * (LTA-Cycle de vie dun membre.mdj). Toute transition passe par
 * changerStatut() : elle est validee contre le diagramme d'etats
 * (MembreStatut::transitionsAutorisees()) puis journalisee.
 */
class MemberLifecycleService
{
    /**
     * @throws ValidationException  si la transition n'est pas prevue par le
     *                              diagramme d'etats-transitions.
     */
    public function changerStatut(
        MemberProfile $membre,
        MembreStatut $cible,
        ?User $par = null,
        ?string $motif = null,
    ): MemberProfile {
        /** @var MembreStatut $courant */
        $courant = $membre->statut;
        $par ??= Auth::user();

        if ($cible === $courant) {
            throw ValidationException::withMessages([
                'statut' => 'Le membre est déjà dans ce statut.',
            ]);
        }

        if (! $courant->peutDevenir($cible)) {
            throw ValidationException::withMessages([
                'statut' => "Transition non autorisée : « {$courant->label()} » → « {$cible->label()} » "
                    .'(voir le cycle de vie d\'un membre).',
            ]);
        }

        return DB::transaction(function () use ($membre, $courant, $cible, $par, $motif) {
            $membre->forceFill([
                'statut' => $cible,
                'statut_change_le' => now(),
                'statut_change_par_id' => $par?->id,
                'statut_motif' => $motif,
            ])->save();

            MemberStatusHistory::create([
                'member_profile_id' => $membre->id,
                'ancien_statut' => $courant,
                'nouveau_statut' => $cible,
                'motif' => $motif,
                'change_par_id' => $par?->id,
            ]);

            return $membre->refresh();
        });
    }

    /** Actif -> EnRetard : non-paiement 4 mois apres echeance (Statuts Art. 7). */
    public function marquerEnRetard(MemberProfile $membre, ?User $par = null, ?string $motif = null): MemberProfile
    {
        return $this->changerStatut($membre, MembreStatut::EnRetard, $par, $motif);
    }

    /** EnRetard -> Actif : regularisation de la cotisation (extension assumee). */
    public function regulariser(MemberProfile $membre, ?User $par = null, ?string $motif = null): MemberProfile
    {
        return $this->changerStatut($membre, MembreStatut::Actif, $par, $motif);
    }

    /** EnRetard -> Exclu : radiation pour non-paiement. */
    public function exclure(MemberProfile $membre, ?User $par = null, ?string $motif = null): MemberProfile
    {
        return $this->changerStatut($membre, MembreStatut::Exclu, $par, $motif);
    }

    /** Actif -> Suspendu : decision du Bureau. */
    public function suspendre(MemberProfile $membre, ?User $par = null, ?string $motif = null): MemberProfile
    {
        return $this->changerStatut($membre, MembreStatut::Suspendu, $par, $motif);
    }

    /** Suspendu -> Actif : reintegration. */
    public function reintegrer(MemberProfile $membre, ?User $par = null, ?string $motif = null): MemberProfile
    {
        return $this->changerStatut($membre, MembreStatut::Actif, $par, $motif);
    }

    /** Actif|Suspendu -> Demissionnaire : demission ecrite. */
    public function enregistrerDemission(MemberProfile $membre, ?User $par = null, ?string $motif = null): MemberProfile
    {
        return $this->changerStatut($membre, MembreStatut::Demissionnaire, $par, $motif);
    }
}
