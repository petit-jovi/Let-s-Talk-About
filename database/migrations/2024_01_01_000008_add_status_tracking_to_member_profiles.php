<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Traçabilité du cycle de vie d'un membre
     * (LTA-Cycle de vie dun membre.mdj) : qui a changé le statut, quand,
     * et pour quel motif. Le détail transition par transition est conservé
     * dans la table d'historique member_status_histories.
     */
    public function up(): void
    {
        Schema::table('member_profiles', function (Blueprint $table) {
            $table->timestamp('statut_change_le')->nullable()->after('statut');
            $table->foreignId('statut_change_par_id')->nullable()->after('statut_change_le')
                ->constrained('users')->nullOnDelete();
            $table->text('statut_motif')->nullable()->after('statut_change_par_id');
        });

        Schema::create('member_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_profile_id')->constrained()->cascadeOnDelete();
            $table->string('ancien_statut')->nullable();
            $table->string('nouveau_statut');
            $table->text('motif')->nullable();
            $table->foreignId('change_par_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->nullable();

            $table->index('member_profile_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_status_histories');

        Schema::table('member_profiles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('statut_change_par_id');
            $table->dropColumn(['statut_change_le', 'statut_motif']);
        });
    }
};
