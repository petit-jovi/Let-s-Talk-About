<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MembreStatut;
use App\Enums\MembreType;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateMemberStatusRequest;
use App\Models\User;
use App\Services\MemberLifecycleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * "Gérer la base des membres" (diagramme de cas d'utilisation, acteur
 * Bureau Exécutif) : liste des membres et pilotage manuel du cycle de vie
 * (LTA-Cycle de vie dun membre.mdj).
 */
class MemberController extends Controller
{
    public function __construct(private readonly MemberLifecycleService $lifecycle)
    {
    }

    public function index(Request $request): View
    {
        $statut = $request->string('statut')->value();
        $type = $request->string('type')->value();
        $q = $request->string('q')->value();

        $membres = User::query()
            ->where('type', UserType::Membre)
            ->with('memberProfile')
            ->whereHas('memberProfile', function ($query) use ($statut, $type) {
                if (in_array($statut, MembreStatut::values(), true)) {
                    $query->where('statut', $statut);
                }
                if (in_array($type, MembreType::values(), true)) {
                    $query->where('type_membre', $type);
                }
            })
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nom', 'ilike', "%{$q}%")
                        ->orWhere('prenom', 'ilike', "%{$q}%")
                        ->orWhere('email', 'ilike', "%{$q}%")
                        ->orWhereRaw("('LTA-' || lpad(numero_lta::text, 3, '0')) ilike ?", ["%{$q}%"]);
                });
            })
            ->orderBy('numero_lta')
            ->paginate(20)
            ->withQueryString();

        $compteurs = [];
        foreach (MembreStatut::cases() as $s) {
            $compteurs[$s->value] = User::where('type', UserType::Membre)
                ->whereHas('memberProfile', fn ($qb) => $qb->where('statut', $s))
                ->count();
        }

        return view('admin.members.index', [
            'membres' => $membres,
            'compteurs' => $compteurs,
            'statutFiltre' => $statut,
            'typeFiltre' => $type,
            'q' => $q,
        ]);
    }

    public function show(User $membre): View
    {
        $this->assertEstMembre($membre);

        $membre->load([
            'memberProfile.statutChangePar',
            'memberProfile.historiquesStatut.changePar',
            'memberProfile.demandeAdhesion',
        ]);

        /** @var MembreStatut $courant */
        $courant = $membre->memberProfile->statut;

        return view('admin.members.show', [
            'membre' => $membre,
            'profile' => $membre->memberProfile,
            'transitions' => $courant->transitionsAutorisees(),
        ]);
    }

    public function updateStatus(UpdateMemberStatusRequest $request, User $membre): RedirectResponse
    {
        $this->assertEstMembre($membre);

        $this->lifecycle->changerStatut(
            $membre->memberProfile,
            MembreStatut::from($request->validated()['statut']),
            $request->user(),
            $request->validated()['motif'] ?? null,
        );

        return redirect()
            ->route('admin.members.show', $membre)
            ->with('status', "Statut de {$membre->nomComplet()} mis à jour.");
    }

    private function assertEstMembre(User $membre): void
    {
        abort_unless($membre->isMembre() && $membre->memberProfile !== null, 404);
    }
}
