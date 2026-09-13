<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\AdminProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Provisionne le bureau fondateur (Statuts Art. 8(1)) afin qu'il existe
     * au moins un compte capable de se connecter et de traiter les
     * demandes d'adhesion des le premier lancement en local.
     *
     * IMPORTANT (developpement local uniquement) : le mot de passe seede
     * est volontairement simple et DOIT etre change avant tout usage hors
     * environnement local. En production, ces comptes doivent plutot etre
     * crees via la procedure d'activation par email comme tout autre compte.
     */
    public function run(): void
    {
        $bureau = [
            ['prenom' => 'Président', 'nom' => 'Fondateur', 'email' => 'president@letstalkabout.local', 'role' => 'president'],
            ['prenom' => 'Secrétaire', 'nom' => 'Général', 'email' => 'secretaire@letstalkabout.local', 'role' => 'secretaire_general'],
            ['prenom' => 'Trésorier', 'nom' => 'LTA', 'email' => 'tresorier@letstalkabout.local', 'role' => 'tresorier'],
            ['prenom' => 'Responsable', 'nom' => 'RH', 'email' => 'rh@letstalkabout.local', 'role' => 'responsable_rh'],
        ];

        foreach ($bureau as $membre) {
            $user = User::query()->firstOrCreate(
                ['email' => $membre['email']],
                [
                    'nom' => $membre['nom'],
                    'prenom' => $membre['prenom'],
                    'type' => UserType::Admin,
                    'password' => Hash::make('lta-dev-password'),
                    'activated_at' => now(),
                    'email_verified_at' => now(),
                ]
            );

            AdminProfile::query()->firstOrCreate(
                ['user_id' => $user->id],
                [
                    'role_bureau' => $membre['role'],
                    'date_debut_mandat' => now()->toDateString(),
                ]
            );

            $user->syncRoles([$membre['role']]);

            // Numero d'adherent LTA : le president (traite en premier) obtient
            // LTA-001, puis le reste du bureau fondateur dans l'ordre.
            $user->attribuerNumeroLta();
        }
    }
}
