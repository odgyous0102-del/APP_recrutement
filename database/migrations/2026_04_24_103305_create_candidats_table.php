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
        Schema::create('candidats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('titre_professionnel')->nullable();
            $table->enum('niveau_experience', ['debutant', 'junior', 'confirme', 'senior', 'expert'])->nullable();
            $table->enum('disponibilite', ['immediate', '1_mois', '3_mois', '6_mois', 'plus_6_mois'])->nullable();
            $table->json('competences')->nullable();
            $table->json('langues')->nullable();
            $table->string('cv_path')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->decimal('pretention_salariale', 10, 2)->nullable();
            $table->integer('score_profil')->default(0);
            $table->string('linkedin_url')->nullable();
            $table->boolean('is_open_to_relocation')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidats');
    }
};
