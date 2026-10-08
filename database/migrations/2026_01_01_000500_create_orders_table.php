<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('canteen_id')->constrained()->cascadeOnDelete();

            // Nomor pesanan unik global. Contoh: A-20260916-027
            $table->string('order_number', 32)->unique();

            // Nomor antrean. Urut per kantin dan di-reset setiap hari.
            $table->unsignedInteger('queue_number');
            $table->date('queue_date');
            // Bentuk tampilan nomor antrean. Contoh: A027
            $table->string('queue_code', 8);

            $table->unsignedInteger('total')->default(0);
            $table->enum('status', ['pending', 'processing', 'ready', 'completed', 'cancelled'])
                ->default('pending');
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->text('cancel_reason')->nullable();
            $table->timestamp('ordered_at');
            $table->timestamps();

            // Kunci utama pencegah nomor antrean kembar pada kantin & hari yang sama.
            $table->unique(['canteen_id', 'queue_date', 'queue_number'], 'orders_queue_unique');
            $table->index(['canteen_id', 'status']);
            $table->index(['student_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
