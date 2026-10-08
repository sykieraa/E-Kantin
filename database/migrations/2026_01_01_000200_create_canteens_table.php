<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('canteens', function (Blueprint $table) {
            $table->id();
            // Pemilik kantin (user dengan role = seller).
            // Dipakai untuk validasi ownership: penjual hanya boleh mengelola kantinnya sendiri.
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('name');
            // Kode 1-2 huruf, dipakai sebagai awalan nomor antrean. Contoh: A -> A001
            $table->string('code', 2)->unique();
            $table->text('description')->nullable();
            $table->enum('status', ['open', 'temporarily_closed', 'closed'])->default('closed');
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('canteens');
    }
};
