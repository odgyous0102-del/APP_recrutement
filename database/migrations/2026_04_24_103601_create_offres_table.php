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
        Schema::create('offres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruteur_id')->constrained()->onDelete('cascade');
            $table->string('titre');
            $table->text('description');
            $table->json('competences_req')->nullable();
            $table->enum('type_contrat', ['cdi', 'cdd', 'stage', 'alternance', 'freelance']);
            $table->string('lieu');
            $table->enum('teletravail', ['non', 'partiel', 'total'])->default('non');
            $table->decimal('salaire_min', 10, 2)->nullable();
            $table->decimal('salaire_max', 10, 2)->nullable();
            $table->enum('experience_req', ['debutant', 'junior', 'confirme', 'senior', 'expert']);
            $table->enum('statut', ['brouillon', 'publie', 'archive'])->default('brouillon');
            $table->date('date_limite')->nullable();
            $table->integer('nb_postes')->default(1);
            $table->integer('vues')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offres');
    }
};
