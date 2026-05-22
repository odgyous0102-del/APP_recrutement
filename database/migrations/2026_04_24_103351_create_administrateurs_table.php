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
        Schema::create('administrateurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('permissions')->nullable();
            $table->string('departement')->nullable();
            $table->enum('niveau_acces', ['basic', 'intermediate', 'full'])->default('basic');
            $table->timestamp('last_login_at')->nullable();
            $table->boolean('can_manage_users')->default(false);
            $table->boolean('can_manage_offres')->default(false);
            $table->boolean('two_factor_enabled')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administrateurs');
    }
};
