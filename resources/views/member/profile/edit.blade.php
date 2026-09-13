@extends('layouts.public')

@section('title', 'Modifier mon profil')

@section('content')
<div class="mx-auto max-w-3xl px-6 py-10">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-extrabold text-lta-primary">Modifier mon profil</h1>
        <a href="{{ route('profile.show') }}" class="text-sm font-semibold text-lta-secondary hover:underline">← Retour</a>
    </div>

    @php $profile = $user->memberProfile; @endphp

    {{-- Informations personnelles --}}
    <form method="POST" action="{{ route('profile.update') }}" class="lta-card space-y-5">
        @csrf
        @method('PUT')

        <h2 class="text-lg font-bold text-lta-dark">Informations personnelles</h2>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="prenom" class="lta-label">Prénom</label>
                <input type="text" id="prenom" name="prenom" value="{{ old('prenom', $user->prenom) }}" required class="lta-input">
                @error('prenom') <p class="lta-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="nom" class="lta-label">Nom</label>
                <input type="text" id="nom" name="nom" value="{{ old('nom', $user->nom) }}" required class="lta-input">
                @error('nom') <p class="lta-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="email" class="lta-label">Adresse e-mail</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="lta-input">
            @error('email') <p class="lta-error">{{ $message }}</p> @enderror
        </div>

        @if ($profile)
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="telephone" class="lta-label">Téléphone</label>
                    <input type="text" id="telephone" name="telephone" value="{{ old('telephone', $profile->telephone) }}" required class="lta-input">
                    @error('telephone') <p class="lta-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="date_naissance" class="lta-label">Date de naissance</label>
                    <input type="date" id="date_naissance" name="date_naissance"
                           value="{{ old('date_naissance', $profile->date_naissance?->format('Y-m-d')) }}" class="lta-input">
                    @error('date_naissance') <p class="lta-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="profession" class="lta-label">Profession</label>
                <input type="text" id="profession" name="profession" value="{{ old('profession', $profile->profession) }}" class="lta-input">
                @error('profession') <p class="lta-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="adresse" class="lta-label">Adresse postale</label>
                <input type="text" id="adresse" name="adresse" value="{{ old('adresse', $profile->adresse) }}" class="lta-input">
                @error('adresse') <p class="lta-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <span class="lta-label">Domaines d'intérêt</span>
                @php $choisis = old('domaines_interet', $profile->domaines_interet ?? []); @endphp
                <div class="grid gap-2 sm:grid-cols-2">
                    @foreach (\App\Enums\DomaineInteret::cases() as $domaine)
                        <label class="flex items-center gap-2 text-sm text-slate-700">
                            <input type="checkbox" name="domaines_interet[]" value="{{ $domaine->value }}"
                                   @checked(in_array($domaine->value, $choisis, true)) class="accent-lta-secondary">
                            {{ $domaine->label() }}
                        </label>
                    @endforeach
                </div>
            </div>
        @endif

        <button type="submit" class="lta-btn">Enregistrer les modifications</button>
    </form>

    {{-- Changement de mot de passe --}}
    <form method="POST" action="{{ route('profile.password.update') }}" class="lta-card mt-6 space-y-5">
        @csrf
        @method('PUT')

        <h2 class="text-lg font-bold text-lta-dark">Mot de passe</h2>

        <div>
            <label for="current_password" class="lta-label">Mot de passe actuel</label>
            <input type="password" id="current_password" name="current_password" required class="lta-input">
            @error('current_password') <p class="lta-error">{{ $message }}</p> @enderror
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="password" class="lta-label">Nouveau mot de passe</label>
                <input type="password" id="password" name="password" required class="lta-input">
                @error('password') <p class="lta-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="password_confirmation" class="lta-label">Confirmer</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="lta-input">
            </div>
        </div>

        <button type="submit" class="lta-btn-outline">Changer le mot de passe</button>
    </form>
</div>
@endsection
