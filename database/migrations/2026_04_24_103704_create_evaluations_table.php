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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entretien_id')->constrained()->onDelete('cascade');
            $table->foreignId('evaluateur_id')->constrained('users')->onDelete('cascade');
            $table->integer('competences_techniques')->default(0);
            $table->integer('communication')->default(0);
            $table->integer('motivation')->default(0);
            $table->integer('culture_fit')->default(0);
            $table->decimal('note_globale', 3, 1)->default(0);
            $table->enum('recommandation', ['fortement_recommande', 'recommande', 'neutre', 'pas_recommande', 'fortement_pas_recommande'])->nullable();
            $table->text('commentaires')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
