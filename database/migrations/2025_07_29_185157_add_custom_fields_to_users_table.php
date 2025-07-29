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
        Schema::table('users', function (Blueprint $table) {
            // Step 1: Naye columns add karo
            $table->foreignId('role_id')->default(2)->after('password');
            $table->boolean('is_active')->default(true)->after('role_id');
            $table->timestamp('last_seen_at')->nullable()->after('is_active');

            // Step 2: Foreign key constraint lagao
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
    */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rollback ke liye pehle constraint drop karo
            $table->dropForeign(['role_id']);
            
            // Phir columns drop karo
            $table->dropColumn(['role_id', 'is_active', 'last_seen_at']);
        });
    }
};
