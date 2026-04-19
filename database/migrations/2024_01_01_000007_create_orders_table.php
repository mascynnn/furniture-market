<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // RELASI USER
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // DATA ORDER
            $table->string('status')->default('pending');
            $table->decimal('total_price', 12, 2);

            // OPSIONAL
            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};