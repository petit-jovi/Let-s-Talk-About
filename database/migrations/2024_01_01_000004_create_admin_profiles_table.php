<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Specialisation "Administrateur" de la classe Utilisateur (Class Diagram).
     * Represente un membre du Bureau Executif (Statuts Art. 8).
     */
    public function up(): void
    {
        Schema::create('admin_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();

            $table->enum('role_bureau', [
                'president',
                'secretaire_general',
                'tresorier',
                'responsable_rh',
                'charge_communication',
                'responsable_activites_culturelles',
                'charge_creation_contenu',
                'responsable_partenariats',
            ]);

            $table->date('date_debut_mandat');
            $table->date('date_fin_mandat')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_profiles');
    }
};
