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
        Schema::create('statistiques_kpi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrateur_id')->nullable()->constrained('administrateurs')->onDelete('set null');
            $table->integer('nb_offres_actives')->default(0);
            $table->integer('nb_candidatures_total')->default(0);
            $table->integer('nb_entretiens_planifies')->default(0);
            $table->integer('nb_recruteurs_en_attente')->default(0);
            $table->decimal('taux_conversion', 5, 2)->default(0);
            $table->date('periode_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistiques_kpi');
    }
};
