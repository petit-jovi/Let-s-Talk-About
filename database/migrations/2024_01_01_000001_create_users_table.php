<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Table "Utilisateur" (classe abstraite du diagramme de classes).
     * Les specialisations Membre / Administrateur sont portees par les
     * tables 1-1 member_profiles / admin_profiles (cf. migrations suivantes).
     *
     * Un compte utilisateur n'est jamais cree par auto-inscription publique :
     * il nait uniquement de la validation d'une DemandeAdhesion par le
     * Bureau Executif, ou est provisionne directement pour un membre du
     * bureau (seeder). Voir MembershipService::approuver().
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            // Nullable tant que le compte n'a pas ete active (mot de passe
            // defini par le titulaire via le lien signe recu par email).
            $table->string('password')->nullable();

            $table->enum('type', ['membre', 'admin']);

            $table->timestamp('activated_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
