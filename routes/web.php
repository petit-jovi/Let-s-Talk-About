<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\MembershipRequestController as AdminMembershipRequestController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Member\ProfileController;
use App\Http\Controllers\Public\AccountActivationController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\MembershipRequestController;
use App\Support\Permissions;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Espace public (acteur "Visiteur")
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/adhesion', [MembershipRequestController::class, 'create'])
    ->name('membership.create');
Route::post('/adhesion', [MembershipRequestController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('membership.store');
Route::get('/adhesion/merci', [MembershipRequestController::class, 'thankYou'])
    ->name('membership.thank-you');

/*
|--------------------------------------------------------------------------
| Authentification (comptes crees exclusivement par le Bureau Executif)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/connexion', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/connexion', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:10,1');

    // Activation initiale du compte (lien recu dans l'email de bienvenue).
    Route::get('/compte/activer/{token}', [AccountActivationController::class, 'create'])
        ->name('password.activate');
    Route::post('/compte/activer', [AccountActivationController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('password.activate.store');

    // Mot de passe oublie (comptes deja actives).
    Route::get('/mot-de-passe/oubli', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('/mot-de-passe/oubli', [PasswordResetLinkController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('password.email');
    Route::get('/mot-de-passe/reinitialiser/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('/mot-de-passe/reinitialiser', [NewPasswordController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('password.store');
});

Route::post('/deconnexion', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Espace personnel "Mon profil" (tout compte authentifie)
| Class Diagram : Utilisateur::mettreAJourProfil()
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/mon-profil', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/mon-profil/edition', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/mon-profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/mon-profil/mot-de-passe', [ProfileController::class, 'updatePassword'])
        ->name('profile.password.update');
});

/*
|--------------------------------------------------------------------------
| Espace Bureau Executif (acteur "Administrateur")
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // --- Demandes d'adhesion ---
        Route::middleware('permission:'.Permissions::DEMANDES_CONSULTER)->group(function () {
            Route::get('/demandes-adhesion', [AdminMembershipRequestController::class, 'index'])
                ->name('membership-requests.index');
            Route::get('/demandes-adhesion/{demande}', [AdminMembershipRequestController::class, 'show'])
                ->name('membership-requests.show');
        });

        Route::post('/demandes-adhesion/{demande}/valider', [AdminMembershipRequestController::class, 'approve'])
            ->middleware('permission:'.Permissions::DEMANDES_VALIDER)
            ->name('membership-requests.approve');

        Route::post('/demandes-adhesion/{demande}/refuser', [AdminMembershipRequestController::class, 'reject'])
            ->middleware('permission:'.Permissions::DEMANDES_REFUSER)
            ->name('membership-requests.reject');

        // --- Base des membres + cycle de vie ---
        Route::middleware('permission:'.Permissions::MEMBRES_GERER)->group(function () {
            Route::get('/membres', [AdminMemberController::class, 'index'])->name('members.index');
            Route::get('/membres/{membre}', [AdminMemberController::class, 'show'])->name('members.show');
            Route::put('/membres/{membre}/statut', [AdminMemberController::class, 'updateStatus'])
                ->name('members.status.update');
        });
    });
