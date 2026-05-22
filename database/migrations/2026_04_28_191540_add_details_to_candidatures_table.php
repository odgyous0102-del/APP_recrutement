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
        Schema::table('candidatures', function (Blueprint $table) {
            $table->string('disponibilite')->nullable()->after('lettre_motivation');
            $table->decimal('pretention_salariale', 12, 2)->nullable()->after('disponibilite');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidatures', function (Blueprint $table) {
            $table->dropColumn(['disponibilite', 'pretention_salariale']);
        });
    }
};
