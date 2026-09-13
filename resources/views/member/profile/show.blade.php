@extends('layouts.public')

@section('title', 'Mon profil')

@section('content')
<div class="mx-auto max-w-3xl px-6 py-10">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-extrabold text-lta-primary">Mon profil</h1>
        <a href="{{ route('profile.edit') }}" class="lta-btn !py-1.5 !px-5">Modifier</a>
    </div>

    @php
        $profile = $user->memberProfile;
        $badge = $profile ? match ($profile->statut->badgeColor()) {
            'green' => 'bg-emerald-100 text-emerald-700',
            'amber' => 'bg-amber-100 text-amber-700',
            'orange' => 'bg-orange-100 text-orange-700',
            'red' => 'bg-red-100 text-red-700',
            default => 'bg-slate-100 text-slate-700',
        } : '';
    @endphp

    <div class="lta-card">
        <h2 class="mb-4 text-lg font-bold text-lta-dark">Compte</h2>
        <dl class="grid gap-4 text-sm sm:grid-cols-2">
            <div>
                <dt class="text-slate-500">Numéro d'adhérent</dt>
                <dd class="font-semibold text-lta-dark">{{ $user->numeroLtaFormate() ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-slate-500">Type de compte</dt>
                <dd class="font-semibold text-lta-dark">
                    {{ $user->isAdmin() ? 'Bureau Exécutif — '.$user->adminProfile?->role_bureau?->label() : 'Membre' }}
                </dd>
            </div>
            <div>
                <dt class="text-slate-500">Nom</dt>
                <dd class="font-semibold text-lta-dark">{{ $user->nom }}</dd>
            </div>
            <div>
                <dt class="text-slate-500">Prénom</dt>
                <dd class="font-semibold text-lta-dark">{{ $user->prenom }}</dd>
            </div>
            <div>
                <dt class="text-slate-500">Adresse e-mail</dt>
                <dd class="font-semibold text-lta-dark">{{ $user->email }}</dd>
            </div>
            <div>
                <dt class="text-slate-500">Inscrit depuis</dt>
                <dd class="font-semibold text-lta-dark">{{ $user->dateInscription()?->format('d/m/Y') ?? '—' }}</dd>
            </div>
        </dl>
    </div>

    @if ($profile)
        <div class="lta-card mt-6">
            <h2 class="mb-4 text-lg font-bold text-lta-dark">Adhésion</h2>
            <dl class="grid gap-4 text-sm sm:grid-cols-2">
                <div>
                    <dt class="text-slate-500">Type de membre</dt>
                    <dd class="font-semibold text-lta-dark">{{ $profile->type_membre->label() }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Statut</dt>
                    <dd><span class="lta-badge {{ $badge }}">{{ $profile->statut->label() }}</span></dd>
                </div>
                <div>
                    <dt class="text-slate-500">Date d'adhésion</dt>
                    <dd class="font-semibold text-lta-dark">{{ $profile->date_adhesion?->format('d/m/Y') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Téléphone</dt>
                    <dd class="font-semibold text-lta-dark">{{ $profile->telephone ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Date de naissance</dt>
                    <dd class="font-semibold text-lta-dark">{{ $profile->date_naissance?->format('d/m/Y') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Profession</dt>
                    <dd class="font-semibold text-lta-dark">{{ $profile->profession ?? '—' }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-slate-500">Adresse postale</dt>
                    <dd class="font-semibold text-lta-dark">{{ $profile->adresse ?? '—' }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-slate-500">Domaines d'intérêt</dt>
                    <dd class="mt-1 flex flex-wrap gap-2">
                        @forelse ($profile->domaines_interet ?? [] as $domaine)
                            <span class="lta-badge bg-lta-blush/20 text-lta-dark">{{ \App\Enums\DomaineInteret::from($domaine)->label() }}</span>
                        @empty
                            <span class="text-slate-500">—</span>
                        @endforelse
                    </dd>
                </div>
            </dl>
            <p class="mt-4 border-t border-lta-dark/10 pt-3 text-xs text-slate-400">
                Le type de membre et le statut sont gérés par le Bureau Exécutif.
            </p>
        </div>
    @endif
</div>
@endsection
