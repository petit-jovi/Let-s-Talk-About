<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

/**
 * "Mot de passe oublié" : envoi du lien de réinitialisation.
 * Concerne tous les comptes (membre ou Bureau) déjà activés.
 */
class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Réponse volontairement neutre : ne révèle pas si l'email existe.
        Password::sendResetLink($request->only('email'));

        return back()->with('status', "Si un compte est associé à cette adresse, un email de réinitialisation vient d'être envoyé.");
    }
}
