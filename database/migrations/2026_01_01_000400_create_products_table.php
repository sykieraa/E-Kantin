<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('canteen_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            // Harga rupiah disimpan sebagai integer (tanpa sen) agar tidak ada
            // masalah pembulatan seperti pada tipe float.
            $table->unsignedInteger('price')->default(0);
            $table->unsignedInteger('stock')->default(0);
            $table->string('image')->nullable();
            $table->boolean('is_available')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['canteen_id', 'is_available']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
