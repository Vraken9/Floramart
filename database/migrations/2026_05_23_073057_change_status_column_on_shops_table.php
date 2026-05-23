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
        Schema::table('shops', function (Blueprint $table) {
            // Because changing enum requires doctrine/dbal, a safer way in Laravel 10+ without dbal is:
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE shops MODIFY COLUMN status ENUM('pending', 'in_review', 'approved', 'rejected', 'suspended', 'banned') DEFAULT 'pending'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE shops MODIFY COLUMN status ENUM('pending', 'approved', 'suspended') DEFAULT 'pending'");
        });
    }
};
