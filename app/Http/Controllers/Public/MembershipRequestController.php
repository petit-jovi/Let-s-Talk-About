<?php

namespace App\Http\Controllers\Public;

use App\Enums\DomaineInteret;
use App\Enums\MembreType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDemandeAdhesionRequest;
use App\Services\MembershipService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Formulaire public de demande d'adhesion (acteur "Visiteur").
 * Cf. LTA-SequenceDiagram-Soumission&Validation_Adhesion.mdj, messages 1-6.
 */
class MembershipRequestController extends Controller
{
    public function __construct(private readonly MembershipService $membershipService)
    {
    }

    public function create(): View
    {
        return view('membership.create', [
            'typesMembre' => MembreType::ouvertsALaDemande(),
            'domaines' => DomaineInteret::cases(),
        ]);
    }

    public function store(StoreDemandeAdhesionRequest $request): RedirectResponse
    {
        $this->membershipService->soumettre(
            $request->donneesValidees(),
            $request->ip()
        );

        // Message 5 : "Afficher message 'Demande en cours de traitement'".
        return redirect()
            ->route('membership.thank-you')
            ->with('demande_soumise', true);
    }

    public function thankYou(): View
    {
        return view('membership.thank-you');
    }
}
