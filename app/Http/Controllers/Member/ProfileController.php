<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\UpdatePasswordRequest;
use App\Http\Requests\Member\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/**
 * Espace personnel "Mon profil" (Class Diagram : Utilisateur::mettreAJourProfil()).
 * Accessible à tout compte authentifié — membre comme membre du Bureau.
 * Consultation + édition des informations personnelles, et changement de
 * mot de passe.
 */
class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        return view('member.profile.show', [
            'user' => $request->user()->load('memberProfile', 'adminProfile'),
        ]);
    }

    public function edit(Request $request): View
    {
        return view('member.profile.edit', [
            'user' => $request->user()->load('memberProfile'),
        ]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $user->update([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
        ]);

        if ($profile = $user->memberProfile) {
            $profile->update([
                'telephone' => $data['telephone'] ?? $profile->telephone,
                'adresse' => $data['adresse'] ?? null,
                'date_naissance' => $data['date_naissance'] ?? null,
                'profession' => $data['profession'] ?? null,
                'domaines_interet' => $data['domaines_interet'] ?? [],
            ]);
        }

        return redirect()->route('profile.show')->with('status', 'Votre profil a été mis à jour.');
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $request->user()->update([
            'password' => Hash::make($request->validated()['password']),
        ]);

        return redirect()->route('profile.show')->with('status', 'Votre mot de passe a été modifié.');
    }
}
