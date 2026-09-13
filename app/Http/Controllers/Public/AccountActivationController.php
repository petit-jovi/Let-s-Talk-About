<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Definition du mot de passe par le titulaire d'un compte cree par le
 * Bureau Executif (adhesion validee) - reutilise le mecanisme standard de
 * reinitialisation de mot de passe de Laravel (table password_reset_tokens,
 * jeton signe a usage unique, expiration 60 min par defaut cf config/auth.php).
 *
 * Ce meme ecran/flux sert egalement de "mot de passe oublie" pour tous les
 * comptes (membre ou admin).
 */
class AccountActivationController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.activate-account', [
            'token' => $request->route('token'),
            'email' => $request->query('email', ''),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'activated_at' => $user->activated_at ?? now(),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => __($status),
            ]);
        }

        return redirect()->route('login')->with('status', 'Votre mot de passe a été défini, vous pouvez maintenant vous connecter.');
    }
}
