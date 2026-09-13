@extends('layouts.auth')

@section('title', 'Connexion')

@section('content')
    <h1 class="mb-6 text-center text-xl font-bold text-lta-dark">Connexion à votre espace</h1>

    @if (session('status'))
        <div class="mb-4 rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="lta-label">Adresse e-mail</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="lta-input">
        </div>

        <div>
            <label for="password" class="lta-label">Mot de passe</label>
            <input type="password" id="password" name="password" required class="lta-input">
        </div>

        @error('email')
            <p class="lta-error">{{ $message }}</p>
        @enderror

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" class="accent-lta-secondary">
                Se souvenir de moi
            </label>

            <a href="{{ route('password.request') }}" class="text-sm font-semibold text-lta-secondary hover:underline">
                Mot de passe oublié ?
            </a>
        </div>

        <button type="submit" class="lta-btn w-full">Se connecter</button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        Les comptes sont créés par le Bureau Exécutif après validation d'une demande d'adhésion.
    </p>
@endsection
