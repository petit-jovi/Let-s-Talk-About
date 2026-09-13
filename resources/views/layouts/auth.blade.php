<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Connexion') — LET'S TALK ABOUT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-lta-dark px-4">
    <div class="w-full max-w-md">
        <div class="mb-8 text-center">
            <a href="{{ route('home') }}" class="text-2xl font-extrabold tracking-tight text-white">
                LET'S <span class="text-lta-blush">TALK</span> ABOUT
            </a>
        </div>

        <div class="lta-card">
            @yield('content')
        </div>

        <p class="mt-6 text-center text-sm text-white/60">
            <a href="{{ route('home') }}" class="hover:text-white">← Retour au site</a>
        </p>
    </div>
</body>
</html>
