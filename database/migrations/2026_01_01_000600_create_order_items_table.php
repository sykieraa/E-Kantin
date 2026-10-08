<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            // Boleh null supaya riwayat tetap utuh walau produk dihapus permanen.
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();

            // Snapshot: nama & harga disimpan apa adanya saat transaksi terjadi,
            // sehingga riwayat tidak ikut berubah ketika produk diedit.
            $table->string('product_name');
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('price');
            $table->unsignedInteger('subtotal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
