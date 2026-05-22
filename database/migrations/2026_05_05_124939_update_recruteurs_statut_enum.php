<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE recruteurs MODIFY COLUMN statut_verification ENUM('en_attente', 'valide', 'rejete', 'approuve') DEFAULT 'en_attente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE recruteurs MODIFY COLUMN statut_verification ENUM('en_attente', 'valide', 'rejete') DEFAULT 'en_attente'");
    }
};
