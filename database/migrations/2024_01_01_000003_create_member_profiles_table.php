<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Specialisation "Membre" de la classe Utilisateur (Class Diagram).
     * 1-1 avec users. Cree automatiquement lors de la validation d'une
     * DemandeAdhesion (jamais directement par l'utilisateur).
     */
    public function up(): void
    {
        Schema::create('member_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();

            $table->enum('type_membre', ['fondateur', 'actif', 'sympathisant', 'honneur']);
            $table->enum('statut', ['actif', 'en_retard', 'suspendu', 'exclu', 'demissionnaire'])
                ->default('actif');

            $table->date('date_adhesion');

            // Donnees personnelles sensibles chiffrees au repos (cast "encrypted" cote modele).
            $table->text('telephone')->nullable();
            $table->text('adresse')->nullable();

            $table->date('date_naissance')->nullable();
            $table->string('profession')->nullable();

            // Domaines d'interet (Statuts Art. 3) - tableau json de valeurs DomaineInteret.
            $table->json('domaines_interet')->nullable();

            $table->foreignId('demande_adhesion_id')->nullable()
                ->constrained('demande_adhesions')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_profiles');
    }
};
