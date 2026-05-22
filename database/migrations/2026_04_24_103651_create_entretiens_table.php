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
        Schema::create('entretiens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidature_id')->constrained()->onDelete('cascade');
            $table->foreignId('planifie_par')->constrained('users')->onDelete('cascade');
            $table->dateTime('date_entretien');
            $table->integer('duree_minutes')->default(60);
            $table->enum('type', ['telephonique', 'visio', 'physique'])->default('visio');
            $table->string('lien_visio')->nullable();
            $table->enum('statut', ['planifie', 'confirme', 'annule', 'termine'])->default('planifie');
            $table->enum('resultat', ['en_attente', 'favorable', 'defavorable', 'reserve'])->nullable();
            $table->text('compte_rendu')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entretiens');
    }
};
