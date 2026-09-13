<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name')) — LET'S TALK ABOUT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-lta-cream">

    <header class="bg-lta-dark text-white shadow-lg">
        <nav class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
            <a href="{{ route('home') }}" class="text-xl font-extrabold tracking-tight">
                LET'S <span class="text-lta-blush">TALK</span> ABOUT
            </a>

            <div class="hidden items-center gap-6 text-sm font-medium md:flex">
                <a href="{{ route('home') }}" class="transition hover:text-lta-blush">Accueil</a>
                <a href="{{ route('membership.create') }}" class="transition hover:text-lta-blush">Adhérer</a>

                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="transition hover:text-lta-blush">Espace Bureau</a>
                    @endif
                    <a href="{{ route('profile.show') }}" class="transition hover:text-lta-blush">Mon profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="lta-btn !bg-lta-secondary !py-1.5 !px-4 hover:!bg-lta-primary">
                            Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="lta-btn !py-1.5 !px-5">Connexion</a>
                @endauth
            </div>
        </nav>
    </header>

    <main class="flex-1">
        @if (session('status'))
            <div class="mx-auto mt-6 max-w-3xl rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="mt-16 bg-lta-dark text-lta-blush">
        <div class="mx-auto max-w-6xl px-6 py-8 text-sm">
            <p class="font-semibold text-white">LET'S TALK ABOUT (LTA)</p>
            <p>Association culturelle à but non lucratif — Douala, Cameroun</p>
            <p class="mt-2">letstaboutableta@gmail.com</p>
        </div>
    </footer>
</body>
</html>
