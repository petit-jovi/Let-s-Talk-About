<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DemandeAdhesionStatut;
use App\Http\Controllers\Controller;
use App\Models\DemandeAdhesion;
use App\Services\MembershipService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Espace Bureau Executif : consultation et traitement des demandes
 * d'adhesion (messages 7-17 du diagramme de sequence).
 *
 * L'autorisation fine (qui peut consulter/valider/refuser) est appliquee au
 * niveau des routes via le middleware `permission:...` de Spatie
 * (voir routes/web.php) plutot que dans le constructeur, conformement a la
 * structure de controleurs "sans parent" introduite par Laravel 11.
 */
class MembershipRequestController extends Controller
{
    public function __construct(private readonly MembershipService $membershipService)
    {
    }

    public function index(Request $request): View
    {
        $statutFiltre = $request->string('statut')->value() ?: DemandeAdhesionStatut::EnAttente->value;

        $demandes = DemandeAdhesion::query()
            ->when(
                in_array($statutFiltre, array_map(fn ($c) => $c->value, DemandeAdhesionStatut::cases()), true),
                fn ($query) => $query->where('statut', $statutFiltre)
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.membership-requests.index', [
            'demandes' => $demandes,
            'statutFiltre' => $statutFiltre,
            'compteurs' => [
                'en_attente' => DemandeAdhesion::query()->where('statut', DemandeAdhesionStatut::EnAttente)->count(),
                'approuvee' => DemandeAdhesion::query()->where('statut', DemandeAdhesionStatut::Approuvee)->count(),
                'rejetee' => DemandeAdhesion::query()->where('statut', DemandeAdhesionStatut::Rejetee)->count(),
            ],
        ]);
    }

    public function show(DemandeAdhesion $demande): View
    {
        return view('admin.membership-requests.show', ['demande' => $demande]);
    }

    public function approve(DemandeAdhesion $demande, Request $request): RedirectResponse
    {
        $this->membershipService->approuver($demande, $request->user());

        return redirect()
            ->route('admin.membership-requests.index')
            ->with('status', "Demande de {$demande->nomComplet()} validée : le compte membre a été créé et l'email de bienvenue envoyé.");
    }

    public function reject(DemandeAdhesion $demande, Request $request): RedirectResponse
    {
        $request->validate([
            'motif_refus' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->membershipService->rejeter($demande, $request->user(), $request->input('motif_refus'));

        return redirect()
            ->route('admin.membership-requests.index')
            ->with('status', "Demande de {$demande->nomComplet()} refusée.");
    }
}
