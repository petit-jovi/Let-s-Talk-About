@extends('layouts.public')

@section('title', 'Accueil')

@section('content')
    <section class="bg-lta-dark text-white">
        <div class="mx-auto max-w-6xl px-6 py-20 text-center">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-lta-blush">Association culturelle — Douala, Cameroun</p>
            <h1 class="mt-4 text-4xl font-extrabold leading-tight sm:text-5xl">
                Let's Talk About la <span class="text-lta-blush">culture pop</span> camerounaise & africaine
            </h1>
            <p class="mx-auto mt-6 max-w-2xl text-white/80">
                Musique, cinéma, jeux vidéo, mode, art, bande dessinée, animation, médias sociaux :
                nous rassemblons, produisons et valorisons la créativité locale.
            </p>
            <div class="mt-8 flex justify-center gap-4">
                <a href="{{ route('membership.create') }}" class="lta-btn">Faire une demande d'adhésion</a>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-6 py-16">
        <h2 class="text-center text-2xl font-bold text-lta-dark">Nos domaines d'action</h2>
        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach (['Musique', 'Cinéma', 'Jeux vidéo', 'Mode', 'Art', 'Bande dessinée', 'Animation', 'Médias sociaux'] as $domaine)
                <div class="lta-frame">
                    <div class="flex h-28 items-center justify-center rounded-lg bg-white/95 text-center font-semibold text-lta-dark">
                        {{ $domaine }}
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="bg-lta-blush/20">
        <div class="mx-auto max-w-4xl px-6 py-16 text-center">
            <h2 class="text-2xl font-bold text-lta-dark">Envie de nous rejoindre ?</h2>
            <p class="mt-4 text-slate-700">
                Devenez membre actif ou sympathisant et participez à la vie de l'association :
                événements, contenus exclusifs, votes en Assemblée Générale et bien plus.
            </p>
            <a href="{{ route('membership.create') }}" class="lta-btn mt-6 inline-flex">Soumettre ma demande d'adhésion</a>
        </div>
    </section>
@endsection
