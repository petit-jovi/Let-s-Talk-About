<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Le President dispose des "pouvoirs les plus etendus" (Statuts Art. 8bis) :
        // super-admin implicite sur toutes les permissions applicatives.
        Gate::before(function ($user, string $ability) {
            return $user->hasRole('president') ? true : null;
        });

        // Exigence de robustesse des mots de passe (comptes membres et bureau).
        // NB : uncompromised() (verification Have I Been Pwned) est volontairement
        // omis ici pour ne pas dependre d'un acces reseau externe en local ;
        // a activer en production si l'environnement a un acces internet fiable.
        Password::defaults(fn () => Password::min(10)
            ->letters()
            ->mixedCase()
            ->numbers()
        );

        if (! $this->app->environment('local')) {
            URL::forceScheme('https');
        }
    }
}
