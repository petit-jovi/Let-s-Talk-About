@extends('layouts.admin')

@section('title', "Membre — {$membre->nomComplet()}")
@section('page-title', 'Fiche membre')

@section('content')
    <a href="{{ route('admin.members.index') }}" class="mb-4 inline-block text-sm font-semibold text-lta-secondary hover:underline">
        ← Retour à la base des membres
    </a>

    @php
        $badge = match ($profile->statut->badgeColor()) {
            'green' => 'bg-emerald-100 text-emerald-700',
            'amber' => 'bg-amber-100 text-amber-700',
            'orange' => 'bg-orange-100 text-orange-700',
            'red' => 'bg-red-100 text-red-700',
            default => 'bg-slate-100 text-slate-700',
        };
    @endphp

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lta-card lg:col-span-2">
            <div class="mb-4 flex items-start justify-between">
                <div>
                    <h2 class="text-xl font-bold text-lta-dark">{{ $membre->nomComplet() }}</h2>
                    <p class="text-slate-500">
                        <span class="font-mono text-xs">{{ $membre->numeroLtaFormate() }}</span>
                        · {{ $membre->email }}
                    </p>
                </div>
                <span class="lta-badge {{ $badge }}">{{ $profile->statut->label() }}</span>
            </div>

            <dl class="grid gap-4 border-t border-lta-dark/10 pt-4 text-sm sm:grid-cols-2">
                <div><dt class="text-slate-500">Type de membre</dt><dd class="font-semibold text-lta-dark">{{ $profile->type_membre->label() }}</dd></div>
                <div><dt class="text-slate-500">Date d'adhésion</dt><dd class="font-semibold text-lta-dark">{{ $profile->date_adhesion?->format('d/m/Y') ?? '—' }}</dd></div>
                <div><dt class="text-slate-500">Téléphone</dt><dd class="font-semibold text-lta-dark">{{ $profile->telephone ?? '—' }}</dd></div>
                <div><dt class="text-slate-500">Profession</dt><dd class="font-semibold text-lta-dark">{{ $profile->profession ?? '—' }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-slate-500">Adresse</dt><dd class="font-semibold text-lta-dark">{{ $profile->adresse ?? '—' }}</dd></div>
                <div><dt class="text-slate-500">Inscrit depuis</dt><dd class="font-semibold text-lta-dark">{{ $membre->dateInscription()?->format('d/m/Y') }}</dd></div>
                <div><dt class="text-slate-500">Compte activé</dt><dd class="font-semibold text-lta-dark">{{ $membre->estActive() ? 'Oui' : 'En attente d\'activation' }}</dd></div>
            </dl>

            @if ($profile->statut_change_le)
                <p class="mt-4 border-t border-lta-dark/10 pt-4 text-xs text-slate-400">
                    Dernier changement de statut le {{ $profile->statut_change_le->format('d/m/Y à H:i') }}
                    @if ($profile->statutChangePar) par {{ $profile->statutChangePar->nomComplet() }} @endif
                    @if ($profile->statut_motif) — « {{ $profile->statut_motif }} » @endif
                </p>
            @endif
        </div>

        {{-- Changement de statut --}}
        <div class="space-y-6">
            <div class="lta-card">
                <h3 class="mb-3 font-bold text-lta-dark">Cycle de vie</h3>

                @if (empty($transitions))
                    <p class="text-sm text-slate-500">
                        Statut terminal (« {{ $profile->statut->label() }} ») : aucune transition possible.
                    </p>
                @else
                    <form method="POST" action="{{ route('admin.members.status.update', $membre) }}" class="space-y-4"
                          onsubmit="return confirm('Confirmer le changement de statut ?');">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="statut" class="lta-label">Nouveau statut</label>
                            <select id="statut" name="statut" required class="lta-input">
                                <option value="" disabled selected>Choisir une transition…</option>
                                @foreach ($transitions as $cible)
                                    <option value="{{ $cible->value }}">
                                        {{ $profile->statut->libelleTransitionVers($cible) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('statut') <p class="lta-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="motif" class="lta-label">Motif (recommandé)</label>
                            <textarea id="motif" name="motif" rows="3" class="lta-input"
                                      placeholder="Décision du Bureau du…, démission reçue le…, cotisation régularisée…">{{ old('motif') }}</textarea>
                            @error('motif') <p class="lta-error">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="lta-btn w-full">Appliquer</button>
                    </form>
                    <p class="mt-3 text-xs text-slate-400">
                        Transitions limitées au diagramme « Cycle de vie d'un membre ».
                    </p>
                @endif
            </div>
        </div>
    </div>

    {{-- Historique --}}
    <div class="lta-card mt-6">
        <h3 class="mb-4 font-bold text-lta-dark">Historique des statuts</h3>
        @forelse ($profile->historiquesStatut as $h)
            <div class="flex items-start gap-3 border-b border-lta-dark/5 py-3 last:border-0 text-sm">
                <div class="w-40 shrink-0 text-slate-400">{{ $h->created_at?->format('d/m/Y H:i') }}</div>
                <div>
                    <p class="text-lta-dark">
                        <span class="font-semibold">{{ $h->ancien_statut?->label() ?? 'Création' }}</span>
                        → <span class="font-semibold">{{ $h->nouveau_statut->label() }}</span>
                    </p>
                    <p class="text-xs text-slate-500">
                        @if ($h->changePar) par {{ $h->changePar->nomComplet() }} @endif
                        @if ($h->motif) — {{ $h->motif }} @endif
                    </p>
                </div>
            </div>
        @empty
            <p class="text-sm text-slate-400">Aucun changement enregistré.</p>
        @endforelse
    </div>
@endsection
