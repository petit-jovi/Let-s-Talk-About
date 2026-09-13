@extends('layouts.admin')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')
    <div class="grid gap-6 sm:grid-cols-2">
        <div class="lta-card">
            <p class="text-sm font-medium text-slate-500">Demandes d'adhésion en attente</p>
            <p class="mt-2 text-4xl font-extrabold text-lta-primary">{{ $demandesEnAttente }}</p>
            @can(\App\Support\Permissions::DEMANDES_CONSULTER)
                <a href="{{ route('admin.membership-requests.index') }}" class="mt-4 inline-block text-sm font-semibold text-lta-secondary hover:underline">
                    Traiter les demandes →
                </a>
            @endcan
        </div>

        <div class="lta-card">
            <p class="text-sm font-medium text-slate-500">Membres actifs</p>
            <p class="mt-2 text-4xl font-extrabold text-lta-dark">{{ $membresActifs }}</p>
        </div>
    </div>

    @if ($dernieresDemandes->isNotEmpty())
        <div class="lta-card mt-8">
            <h2 class="mb-4 text-lg font-bold text-lta-dark">Dernières demandes en attente</h2>
            <ul class="divide-y divide-lta-dark/10">
                @foreach ($dernieresDemandes as $demande)
                    <li class="flex items-center justify-between py-3">
                        <div>
                            <p class="font-semibold text-lta-dark">{{ $demande->nomComplet() }}</p>
                            <p class="text-sm text-slate-500">{{ $demande->created_at->diffForHumans() }}</p>
                        </div>
                        @can(\App\Support\Permissions::DEMANDES_CONSULTER)
                            <a href="{{ route('admin.membership-requests.show', $demande) }}" class="text-sm font-semibold text-lta-secondary hover:underline">
                                Voir →
                            </a>
                        @endcan
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection
