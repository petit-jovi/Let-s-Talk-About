<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Espace Bureau') — LET'S TALK ABOUT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen bg-lta-cream">

    <aside class="flex w-64 flex-shrink-0 flex-col bg-lta-dark text-white">
        <div class="border-b border-white/10 px-6 py-5">
            <a href="{{ route('admin.dashboard') }}" class="text-lg font-extrabold tracking-tight">
                LET'S <span class="text-lta-blush">TALK</span> ABOUT
            </a>
            <p class="mt-0.5 text-xs uppercase tracking-wide text-white/50">Espace Bureau Exécutif</p>
        </div>

        <nav class="flex-1 space-y-1 px-3 py-6 text-sm font-medium">
            <a href="{{ route('admin.dashboard') }}"
               class="block rounded-lg px-4 py-2.5 transition {{ request()->routeIs('admin.dashboard') ? 'bg-lta-primary text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                Tableau de bord
            </a>

            @can(\App\Support\Permissions::DEMANDES_CONSULTER)
                <a href="{{ route('admin.membership-requests.index') }}"
                   class="block rounded-lg px-4 py-2.5 transition {{ request()->routeIs('admin.membership-requests.*') ? 'bg-lta-primary text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                    Demandes d'adhésion
                </a>
            @endcan

            @can(\App\Support\Permissions::MEMBRES_GERER)
                <a href="{{ route('admin.members.index') }}"
                   class="block rounded-lg px-4 py-2.5 transition {{ request()->routeIs('admin.members.*') ? 'bg-lta-primary text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                    Base des membres
                </a>
            @endcan
        </nav>

        <div class="border-t border-white/10 px-6 py-4 text-xs text-white/60">
            <p class="font-semibold text-white">{{ auth()->user()->nomComplet() }}</p>
            <p>{{ auth()->user()->adminProfile?->role_bureau?->label() }}</p>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="text-lta-blush transition hover:text-white">
                    Se déconnecter
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1">
        <header class="flex items-center justify-between border-b border-lta-dark/10 bg-white px-8 py-4 shadow-sm">
            <h1 class="text-lg font-bold text-lta-dark">@yield('page-title', 'Tableau de bord')</h1>
            <a href="{{ route('home') }}" class="text-sm font-medium text-lta-primary hover:underline">
                ← Retour au site public
            </a>
        </header>

        <main class="p-8">
            @if (session('status'))
                <div class="mb-6 rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
