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
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();

            // 1. Mencatat siapa user yang menyukai.
            // constrained() memastikan ID ini harus ada di tabel users.
            // onDelete('cascade') artinya jika akun user dihapus, data wishlist-nya ikut terhapus otomatis.
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // 2. Mencatat produk apa yang disukai.
            // onDelete('cascade') artinya jika produk dihapus oleh Owner, data di wishlist user juga bersih.
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
