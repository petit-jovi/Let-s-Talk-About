@extends('layouts.admin')

@section('title', "Demandes d'adhésion")
@section('page-title', "Demandes d'adhésion")

@section('content')
    <div class="mb-6 flex gap-2">
        @foreach ([
            'en_attente' => "En attente ({$compteurs['en_attente']})",
            'approuvee' => "Approuvées ({$compteurs['approuvee']})",
            'rejetee' => "Refusées ({$compteurs['rejetee']})",
        ] as $valeur => $libelle)
            <a href="{{ route('admin.membership-requests.index', ['statut' => $valeur]) }}"
               class="rounded-full px-4 py-2 text-sm font-semibold transition {{ $statutFiltre === $valeur ? 'bg-lta-primary text-white' : 'bg-white text-lta-dark hover:bg-lta-blush/20' }}">
                {{ $libelle }}
            </a>
        @endforeach
    </div>

    <div class="lta-card overflow-x-auto">
        <table class="w-full min-w-[640px] text-left text-sm">
            <thead>
                <tr class="border-b border-lta-dark/10 text-slate-500">
                    <th class="pb-3 font-medium">Demandeur</th>
                    <th class="pb-3 font-medium">Type souhaité</th>
                    <th class="pb-3 font-medium">Soumise le</th>
                    <th class="pb-3 font-medium">Statut</th>
                    <th class="pb-3 font-medium"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-lta-dark/5">
                @forelse ($demandes as $demande)
                    <tr>
                        <td class="py-3">
                            <p class="font-semibold text-lta-dark">{{ $demande->nomComplet() }}</p>
                            <p class="text-slate-500">{{ $demande->email }}</p>
                        </td>
                        <td class="py-3">{{ $demande->type_membre_souhaite->label() }}</td>
                        <td class="py-3">{{ $demande->created_at->format('d/m/Y H:i') }}</td>
                        <td class="py-3">
                            @php
                                // Classes Tailwind ecrites en toutes lettres (le JIT ne detecte pas
                                // les classes construites dynamiquement par interpolation).
                                $badgeClasses = match ($demande->statut->badgeColor()) {
                                    'amber' => 'bg-amber-100 text-amber-700',
                                    'green' => 'bg-emerald-100 text-emerald-700',
                                    'red' => 'bg-red-100 text-red-700',
                                    default => 'bg-slate-100 text-slate-700',
                                };
                            @endphp
                            <span class="lta-badge {{ $badgeClasses }}">
                                {{ $demande->statut->label() }}
                            </span>
                        </td>
                        <td class="py-3 text-right">
                            <a href="{{ route('admin.membership-requests.show', $demande) }}" class="font-semibold text-lta-secondary hover:underline">
                                Consulter
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-500">Aucune demande dans cette catégorie.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $demandes->links() }}
    </div>
@endsection
