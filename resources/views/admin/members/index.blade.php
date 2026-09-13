@extends('layouts.admin')

@section('title', 'Base des membres')
@section('page-title', 'Base des membres')

@php
    use App\Enums\MembreStatut;
    use App\Enums\MembreType;

    if (! function_exists('lta_member_badge')) {
        function lta_member_badge(MembreStatut $s): string
        {
            return match ($s->badgeColor()) {
                'green' => 'bg-emerald-100 text-emerald-700',
                'amber' => 'bg-amber-100 text-amber-700',
                'orange' => 'bg-orange-100 text-orange-700',
                'red' => 'bg-red-100 text-red-700',
                default => 'bg-slate-100 text-slate-700',
            };
        }
    }
@endphp

@section('content')
    {{-- Filtres par statut --}}
    <div class="mb-6 flex flex-wrap gap-2">
        <a href="{{ route('admin.members.index', array_filter(['q' => $q, 'type' => $typeFiltre])) }}"
           class="lta-badge {{ $statutFiltre === '' ? 'bg-lta-primary text-white' : 'bg-white text-slate-600 border border-slate-200' }}">
            Tous
        </a>
        @foreach (MembreStatut::cases() as $s)
            <a href="{{ route('admin.members.index', array_filter(['statut' => $s->value, 'q' => $q, 'type' => $typeFiltre])) }}"
               class="lta-badge {{ $statutFiltre === $s->value ? 'bg-lta-primary text-white' : 'bg-white text-slate-600 border border-slate-200' }}">
                {{ $s->label() }} ({{ $compteurs[$s->value] }})
            </a>
        @endforeach
    </div>

    {{-- Recherche + filtre type --}}
    <form method="GET" class="mb-6 flex flex-wrap items-end gap-3">
        @if ($statutFiltre !== '')<input type="hidden" name="statut" value="{{ $statutFiltre }}">@endif
        <div>
            <label for="q" class="lta-label">Recherche</label>
            <input type="text" id="q" name="q" value="{{ $q }}" placeholder="Nom, e-mail, LTA-012…" class="lta-input">
        </div>
        <div>
            <label for="type" class="lta-label">Type</label>
            <select id="type" name="type" class="lta-input">
                <option value="">Tous</option>
                @foreach (MembreType::cases() as $t)
                    <option value="{{ $t->value }}" @selected($typeFiltre === $t->value)>{{ $t->label() }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="lta-btn !py-2.5">Filtrer</button>
    </form>

    <div class="lta-card overflow-x-auto p-0">
        <table class="min-w-full divide-y divide-lta-dark/10 text-sm">
            <thead class="bg-lta-cream text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">N° LTA</th>
                    <th class="px-4 py-3">Membre</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3">Adhésion</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-lta-dark/5">
                @forelse ($membres as $membre)
                    <tr class="hover:bg-lta-cream/60">
                        <td class="px-4 py-3 font-mono text-xs text-slate-500">{{ $membre->numeroLtaFormate() }}</td>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-lta-dark">{{ $membre->nomComplet() }}</p>
                            <p class="text-xs text-slate-500">{{ $membre->email }}</p>
                        </td>
                        <td class="px-4 py-3">{{ $membre->memberProfile->type_membre->label() }}</td>
                        <td class="px-4 py-3">
                            <span class="lta-badge {{ lta_member_badge($membre->memberProfile->statut) }}">
                                {{ $membre->memberProfile->statut->label() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-500">{{ $membre->memberProfile->date_adhesion?->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.members.show', $membre) }}" class="font-semibold text-lta-secondary hover:underline">
                                Gérer →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-slate-400">Aucun membre pour ces critères.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $membres->links() }}</div>
@endsection
