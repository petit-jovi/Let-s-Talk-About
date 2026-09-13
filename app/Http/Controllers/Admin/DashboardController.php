<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DemandeAdhesionStatut;
use App\Http\Controllers\Controller;
use App\Models\DemandeAdhesion;
use App\Models\MemberProfile;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'demandesEnAttente' => DemandeAdhesion::query()->where('statut', DemandeAdhesionStatut::EnAttente)->count(),
            'membresActifs' => MemberProfile::query()->where('statut', 'actif')->count(),
            'dernieresDemandes' => DemandeAdhesion::query()
                ->where('statut', DemandeAdhesionStatut::EnAttente)
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}
