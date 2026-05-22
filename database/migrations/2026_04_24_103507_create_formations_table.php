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
        Schema::create('formations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained()->onDelete('cascade');
            $table->string('diplome');
            $table->string('etablissement');
            $table->string('domaine')->nullable();
            $table->enum('niveau', ['licence', 'master', 'doctorat', 'autre'])->nullable();
            $table->integer('date_debut');
            $table->integer('date_fin')->nullable();
            $table->string('mention')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formations');
    }
};
