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
        // 1. Tambah kolom di shops
        Schema::table('shops', function (Blueprint $table) {
            $table->text('rejected_reason')->nullable()->after('status');
            // 'status' column is string in SQLite, we just save strings like 'in_review', 'banned'. No need to alter enum.
        });

        // 2. Tambah kolom di products
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_hidden_by_admin')->default(false)->after('is_active');
        });

        // 3. Buat tabel shop_views
        Schema::create('shop_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_views');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_hidden_by_admin');
        });

        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn('rejected_reason');
        });
    }
};
