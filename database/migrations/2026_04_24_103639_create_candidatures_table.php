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
        Schema::create('candidatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained()->onDelete('cascade');
            $table->foreignId('offre_id')->constrained()->onDelete('cascade');
            $table->string('cv_path')->nullable();
            $table->text('lettre_motivation')->nullable();
            $table->enum('statut', ['en_attente', 'en_cours', 'acceptee', 'refusee'])->default('en_attente');
            $table->enum('etape_recrutement', ['candidature', 'entretien_telephonique', 'entretien_technique', 'entretien_final', 'decision'])->default('candidature');
            $table->integer('score_matching')->default(0);
            $table->text('notes_rh')->nullable();
            $table->timestamp('date_soumission')->useCurrent();
            $table->timestamps();
            
            $table->unique(['candidat_id', 'offre_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidatures');
    }
};
