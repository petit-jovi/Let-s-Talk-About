@extends('layouts.auth')

@section('title', 'Activation du compte')

@section('content')
    <h1 class="mb-2 text-center text-xl font-bold text-lta-dark">Activez votre compte</h1>
    <p class="mb-6 text-center text-sm text-slate-500">Définissez votre mot de passe pour accéder à votre espace membre LTA.</p>

    <form method="POST" action="{{ route('password.activate.store') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label for="email" class="lta-label">Adresse e-mail</label>
            <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required class="lta-input">
            @error('email') <p class="lta-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="lta-label">Nouveau mot de passe</label>
            <input type="password" id="password" name="password" required class="lta-input">
            @error('password') <p class="lta-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="lta-label">Confirmer le mot de passe</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required class="lta-input">
        </div>

        <button type="submit" class="lta-btn w-full">Activer mon compte</button>
    </form>
@endsection
