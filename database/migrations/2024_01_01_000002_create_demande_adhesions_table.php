<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Classe "DemandeAdhesion" du diagramme de classes.
     *
     * Regles metier (Statuts Art. 6(2) + diagramme de sequence
     * Soumission&Validation_Adhesion) :
     *  - soumise par un Visiteur non authentifie (formulaire public) ;
     *  - statut initial "en_attente" ;
     *  - le Bureau Executif n'a PAS a motiver un refus (Art. 6(2)) : le motif
     *    de refus est donc un champ interne, jamais montre au demandeur ;
     *  - l'acceptation des statuts et du reglement interieur est obligatoire
     *    pour deposer la demande.
     */
    public function up(): void
    {
        Schema::create('demande_adhesions', function (Blueprint $table) {
            $table->id();

            $table->string('nom');
            $table->string('prenom');
            $table->string('email');
            $table->text('telephone');

            $table->date('date_naissance')->nullable();
            $table->text('adresse')->nullable();
            $table->string('profession')->nullable();

            $table->enum('type_membre_souhaite', ['actif', 'sympathisant']);

            // Domaines d'interet (Statuts Art. 3) - tableau json de valeurs DomaineInteret.
            $table->json('domaines_interet')->nullable();

            $table->text('motivation');
            $table->string('comment_connu_lta')->nullable();

            $table->boolean('accepte_statuts')->default(false);
            $table->boolean('accepte_reglement_interieur')->default(false);
            $table->boolean('consentement_traitement_donnees')->default(false);

            $table->enum('statut', ['en_attente', 'approuvee', 'rejetee'])
                ->default('en_attente');

            $table->foreignId('traite_par_id')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->timestamp('traite_le')->nullable();

            // Jamais expose au demandeur - reserve au Bureau Executif.
            $table->text('motif_refus')->nullable();
            $table->text('notes_internes')->nullable();

            $table->ipAddress('ip_soumission')->nullable();

            $table->timestamps();

            $table->index('statut');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demande_adhesions');
    }
};
