@extends('layouts.public')

@section('title', 'Demande envoyée')

@section('content')
    <section class="mx-auto max-w-2xl px-6 py-20 text-center">
        <div class="lta-card">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-lta-blush/30 text-3xl">
                ✅
            </div>
            <h1 class="text-2xl font-extrabold text-lta-dark">Demande en cours de traitement</h1>
            <p class="mt-4 text-slate-600">
                Merci pour votre demande d'adhésion à LET'S TALK ABOUT ! Elle a bien été transmise
                au Bureau Exécutif, qui l'examinera dans les meilleurs délais.
            </p>
            <p class="mt-2 text-slate-600">
                Vous recevrez un email dès qu'une décision aura été prise. En cas de validation,
                cet email contiendra un lien pour activer votre compte membre.
            </p>
            <a href="{{ route('home') }}" class="lta-btn mt-8 inline-flex">Retour à l'accueil</a>
        </div>
    </section>
@endsection
