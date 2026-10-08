<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Profil siswa. Relasi one-to-one ke users.
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('nis', 30)->unique();
            $table->string('name');
            $table->string('class_name', 30);
            $table->timestamps();

            $table->index('class_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
