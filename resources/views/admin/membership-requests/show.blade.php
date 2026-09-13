@extends('layouts.admin')

@section('title', "Demande d'adhésion — {$demande->nomComplet()}")
@section('page-title', "Demande d'adhésion")

@section('content')
    <a href="{{ route('admin.membership-requests.index') }}" class="mb-4 inline-block text-sm font-semibold text-lta-secondary hover:underline">
        ← Retour à la liste
    </a>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lta-card lg:col-span-2">
            <div class="mb-4 flex items-start justify-between">
                <div>
                    <h2 class="text-xl font-bold text-lta-dark">{{ $demande->nomComplet() }}</h2>
                    <p class="text-slate-500">{{ $demande->email }} · {{ $demande->telephone }}</p>
                </div>
                @php
                    $badgeClasses = match ($demande->statut->badgeColor()) {
                        'amber' => 'bg-amber-100 text-amber-700',
                        'green' => 'bg-emerald-100 text-emerald-700',
                        'red' => 'bg-red-100 text-red-700',
                        default => 'bg-slate-100 text-slate-700',
                    };
                @endphp
                <span class="lta-badge {{ $badgeClasses }}">{{ $demande->statut->label() }}</span>
            </div>

            <dl class="grid gap-4 border-t border-lta-dark/10 pt-4 text-sm sm:grid-cols-2">
                <div>
                    <dt class="text-slate-500">Type d'adhésion souhaité</dt>
                    <dd class="font-semibold text-lta-dark">{{ $demande->type_membre_souhaite->label() }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Date de naissance</dt>
                    <dd class="font-semibold text-lta-dark">{{ $demande->date_naissance?->format('d/m/Y') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Profession</dt>
                    <dd class="font-semibold text-lta-dark">{{ $demande->profession ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Adresse</dt>
                    <dd class="font-semibold text-lta-dark">{{ $demande->adresse ?? '—' }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-slate-500">Domaines d'intérêt</dt>
                    <dd class="mt-1 flex flex-wrap gap-2">
                        @forelse ($demande->domaines_interet ?? [] as $domaine)
                            <span class="lta-badge bg-lta-blush/20 text-lta-dark">{{ \App\Enums\DomaineInteret::from($domaine)->label() }}</span>
                        @empty
                            <span class="text-slate-500">—</span>
                        @endforelse
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-slate-500">Motivation</dt>
                    <dd class="mt-1 whitespace-pre-line text-lta-dark">{{ $demande->motivation }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-slate-500">Comment il/elle a connu LTA</dt>
                    <dd class="font-semibold text-lta-dark">{{ $demande->comment_connu_lta ?? '—' }}</dd>
                </div>
            </dl>

            <p class="mt-4 border-t border-lta-dark/10 pt-4 text-xs text-slate-400">
                Demande soumise le {{ $demande->created_at->format('d/m/Y à H:i') }}
                @if ($demande->traitePar)
                    · Traitée par {{ $demande->traitePar->nomComplet() }} le {{ $demande->traite_le->format('d/m/Y à H:i') }}
                @endif
            </p>
        </div>

        <div class="space-y-6">
            @if ($demande->estEnAttente())
                @can(\App\Support\Permissions::DEMANDES_VALIDER)
                    <div class="lta-card">
                        <h3 class="mb-3 font-bold text-lta-dark">Valider la demande</h3>
                        <p class="mb-4 text-sm text-slate-500">
                            Un compte membre sera créé automatiquement et un email de bienvenue
                            sera envoyé pour l'activation du mot de passe.
                        </p>
                        <form method="POST" action="{{ route('admin.membership-requests.approve', $demande) }}"
                              onsubmit="return confirm('Confirmer la validation de cette demande ?');">
                            @csrf
                            <button type="submit" class="lta-btn w-full">Valider l'adhésion</button>
                        </form>
                    </div>
                @endcan

                @can(\App\Support\Permissions::DEMANDES_REFUSER)
                    <div class="lta-card">
                        <h3 class="mb-3 font-bold text-lta-dark">Refuser la demande</h3>
                        <p class="mb-4 text-sm text-slate-500">
                            Conformément à l'Article 6(2) des Statuts, aucun motif n'est communiqué
                            au demandeur. La note ci-dessous reste strictement interne.
                        </p>
                        <form method="POST" action="{{ route('admin.membership-requests.reject', $demande) }}"
                              onsubmit="return confirm('Confirmer le refus de cette demande ?');">
                            @csrf
                            <label for="motif_refus" class="lta-label">Note interne (facultatif)</label>
                            <textarea id="motif_refus" name="motif_refus" rows="3" class="lta-input mb-4"></textarea>
                            <button type="submit" class="lta-btn-danger w-full">Refuser l'adhésion</button>
                        </form>
                    </div>
                @endcan
            @else
                <div class="lta-card">
                    <p class="text-sm text-slate-500">Cette demande a déjà été traitée et ne peut plus être modifiée.</p>
                    @if ($demande->motif_refus)
                        <p class="mt-3 text-sm text-lta-dark"><span class="font-semibold">Note interne :</span> {{ $demande->motif_refus }}</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
