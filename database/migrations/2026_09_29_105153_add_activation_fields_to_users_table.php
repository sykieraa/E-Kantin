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
            $table->string('activation_token_hash')
                ->nullable()
                ->after('must_change_password');

            $table->timestamp('activation_expires_at')
                ->nullable()
                ->after('activation_token_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'activation_token_hash',
                'activation_expires_at',
            ]);
        });
    }
};