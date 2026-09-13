@extends('layouts.auth')

@section('title', 'Mot de passe oublié')

@section('content')
    <h1 class="mb-2 text-center text-xl font-bold text-lta-dark">Mot de passe oublié</h1>
    <p class="mb-6 text-center text-sm text-slate-500">
        Indiquez l'adresse e-mail de votre compte : vous recevrez un lien pour définir un nouveau mot de passe.
    </p>

    @if (session('status'))
        <div class="mb-4 rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="lta-label">Adresse e-mail</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="lta-input">
            @error('email') <p class="lta-error">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="lta-btn w-full">Envoyer le lien</button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        <a href="{{ route('login') }}" class="font-semibold text-lta-secondary hover:underline">← Retour à la connexion</a>
    </p>
@endsection
