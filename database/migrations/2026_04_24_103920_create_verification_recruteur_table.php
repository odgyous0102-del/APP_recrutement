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
        Schema::create('verification_recruteur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruteur_id')->constrained()->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('administrateurs')->onDelete('set null');
            $table->enum('statut', ['en_attente', 'valide', 'rejete'])->default('en_attente');
            $table->text('commentaire')->nullable();
            $table->timestamp('date_demande')->useCurrent();
            $table->timestamp('date_decision')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_recruteur');
    }
};
