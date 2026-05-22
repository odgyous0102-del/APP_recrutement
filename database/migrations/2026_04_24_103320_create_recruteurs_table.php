<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recruteurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nom_entreprise');
            $table->string('secteur_activite')->nullable();
            $table->enum('taille_entreprise', ['startup', 'pme', 'grande_entreprise', 'multinationale']);
            $table->string('site_web')->nullable();
            $table->string('logo_entreprise')->nullable();
            $table->text('description_ent')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->enum('statut_verification', ['en_attente', 'valide', 'rejete', 'approuve'])->default('en_attente');
            $table->string('numero_rc')->nullable();
            $table->string('contact_rh_email')->nullable();
            $table->integer('quota_offres')->default(5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruteurs');
    }
};
