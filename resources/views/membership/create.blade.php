@extends('layouts.public')

@section('title', "Demande d'adhésion")

@section('content')
    <section class="mx-auto max-w-3xl px-6 py-12">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-extrabold text-lta-dark">Demande d'adhésion</h1>
            <p class="mt-3 text-slate-600">
                Rejoignez LET'S TALK ABOUT. Votre demande sera examinée par le Bureau Exécutif,
                conformément à l'Article 6 des Statuts de l'association.
            </p>
        </div>

        <form method="POST" action="{{ route('membership.store') }}" class="lta-card space-y-8">
            @csrf

            {{-- Piege a robots : champ invisible qui doit rester vide --}}
            <div class="absolute left-[-9999px]" aria-hidden="true">
                <label for="site_web">Ne pas remplir ce champ</label>
                <input type="text" id="site_web" name="site_web" tabindex="-1" autocomplete="off">
            </div>

            <fieldset class="space-y-5">
                <legend class="text-lg font-bold text-lta-dark">Informations personnelles</legend>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="prenom" class="lta-label">Prénom *</label>
                        <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" required class="lta-input">
                        @error('prenom') <p class="lta-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="nom" class="lta-label">Nom *</label>
                        <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required class="lta-input">
                        @error('nom') <p class="lta-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="email" class="lta-label">Adresse e-mail *</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required class="lta-input">
                        @error('email') <p class="lta-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="telephone" class="lta-label">Téléphone *</label>
                        <input type="tel" id="telephone" name="telephone" value="{{ old('telephone') }}" required class="lta-input" placeholder="+237 6XX XX XX XX">
                        @error('telephone') <p class="lta-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="date_naissance" class="lta-label">Date de naissance</label>
                        <input type="date" id="date_naissance" name="date_naissance" value="{{ old('date_naissance') }}" class="lta-input">
                        @error('date_naissance') <p class="lta-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="profession" class="lta-label">Profession / occupation</label>
                        <input type="text" id="profession" name="profession" value="{{ old('profession') }}" class="lta-input">
                        @error('profession') <p class="lta-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="adresse" class="lta-label">Adresse (ville, quartier)</label>
                    <input type="text" id="adresse" name="adresse" value="{{ old('adresse') }}" class="lta-input">
                    @error('adresse') <p class="lta-error">{{ $message }}</p> @enderror
                </div>
            </fieldset>

            <fieldset class="space-y-5">
                <legend class="text-lg font-bold text-lta-dark">Votre engagement</legend>

                <div>
                    <span class="lta-label">Type d'adhésion souhaité *</span>
                    <div class="mt-2 grid gap-3 sm:grid-cols-2">
                        @foreach ($typesMembre as $type)
                            <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-slate-300 p-4 transition has-[:checked]:border-lta-secondary has-[:checked]:bg-lta-secondary/5">
                                <input type="radio" name="type_membre_souhaite" value="{{ $type->value }}"
                                       {{ old('type_membre_souhaite') === $type->value ? 'checked' : '' }} required class="mt-1 accent-lta-secondary">
                                <span>
                                    <span class="block font-semibold text-lta-dark">{{ $type->label() }}</span>
                                    <span class="block text-sm text-slate-500">
                                        {{ $type->droitDeVote() ? 'Participation active, droit de vote en AG' : 'Soutien à l\'association, invitations aux événements' }}
                                    </span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('type_membre_souhaite') <p class="lta-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <span class="lta-label">Domaines qui vous intéressent</span>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach ($domaines as $domaine)
                            <label class="lta-badge cursor-pointer border border-lta-dark/20 bg-white text-lta-dark has-[:checked]:border-lta-secondary has-[:checked]:bg-lta-secondary has-[:checked]:text-white">
                                <input type="checkbox" name="domaines_interet[]" value="{{ $domaine->value }}"
                                       {{ in_array($domaine->value, old('domaines_interet', [])) ? 'checked' : '' }} class="mr-1.5 accent-lta-secondary">
                                {{ $domaine->label() }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label for="motivation" class="lta-label">Pourquoi souhaitez-vous rejoindre LTA ? *</label>
                    <textarea id="motivation" name="motivation" rows="4" required minlength="20" maxlength="2000"
                              class="lta-input">{{ old('motivation') }}</textarea>
                    @error('motivation') <p class="lta-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="comment_connu_lta" class="lta-label">Comment avez-vous connu LTA ?</label>
                    <input type="text" id="comment_connu_lta" name="comment_connu_lta" value="{{ old('comment_connu_lta') }}" class="lta-input">
                </div>
            </fieldset>

            <fieldset class="space-y-3 border-t border-lta-dark/10 pt-6">
                <legend class="sr-only">Conditions d'admission</legend>

                <label class="flex items-start gap-3 text-sm">
                    <input type="checkbox" name="accepte_statuts" value="1" {{ old('accepte_statuts') ? 'checked' : '' }} required class="mt-1 accent-lta-secondary">
                    <span>J'ai lu et j'accepte intégralement les <a href="#" class="font-semibold text-lta-primary hover:underline">Statuts de l'association</a>. *</span>
                </label>
                @error('accepte_statuts') <p class="lta-error">{{ $message }}</p> @enderror

                <label class="flex items-start gap-3 text-sm">
                    <input type="checkbox" name="accepte_reglement_interieur" value="1" {{ old('accepte_reglement_interieur') ? 'checked' : '' }} required class="mt-1 accent-lta-secondary">
                    <span>J'accepte le règlement intérieur de l'association. *</span>
                </label>
                @error('accepte_reglement_interieur') <p class="lta-error">{{ $message }}</p> @enderror

                <label class="flex items-start gap-3 text-sm">
                    <input type="checkbox" name="consentement_traitement_donnees" value="1" {{ old('consentement_traitement_donnees') ? 'checked' : '' }} required class="mt-1 accent-lta-secondary">
                    <span>J'autorise LTA à traiter mes données personnelles dans le cadre du traitement de ma demande d'adhésion. *</span>
                </label>
                @error('consentement_traitement_donnees') <p class="lta-error">{{ $message }}</p> @enderror
            </fieldset>

            <div class="text-center">
                <button type="submit" class="lta-btn w-full sm:w-auto">Soumettre ma demande d'adhésion</button>
            </div>
        </form>
    </section>
@endsection
