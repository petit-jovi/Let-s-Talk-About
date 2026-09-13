<?php

namespace Database\Seeders;

use App\Enums\BureauRole;
use App\Support\Permissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Cree les permissions, le role "membre" et les 8 roles du Bureau
     * Executif, puis leur assigne les permissions selon la matrice definie
     * dans App\Support\Permissions (voir README.md > "Matrice de permissions").
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (Permissions::all() as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $membre = Role::findOrCreate('membre', 'web');
        $membre->syncPermissions(Permissions::basesMembre());

        $matrice = Permissions::matricePosteBureau();

        foreach (BureauRole::cases() as $poste) {
            $role = Role::findOrCreate($poste->value, 'web');

            $permissions = array_unique(array_merge(
                Permissions::basesAdmin(),
                $matrice[$poste->value] ?? []
            ));

            $role->syncPermissions($permissions);
        }
    }
}
