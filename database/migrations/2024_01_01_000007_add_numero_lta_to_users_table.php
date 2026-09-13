<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Identifiant d'adhérent LTA, séquentiel et stable, attribué à chaque
     * Utilisateur au moment de la création de son compte (membre du Bureau
     * provisionné, ou membre créé après validation d'une DemandeAdhesion).
     *
     * Le président (premier compte provisionné) porte le numéro 1 -> "LTA-001".
     * Le format d'affichage "LTA-%03d" est porté par l'accessor
     * App\Models\User::numeroLtaFormate().
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('numero_lta')->nullable()->unique()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['numero_lta']);
            $table->dropColumn('numero_lta');
        });
    }
};
