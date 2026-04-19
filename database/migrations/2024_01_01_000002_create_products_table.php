<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // RELASI
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // seller
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();

            // BASIC
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // PRICE & STOCK
            $table->decimal('price', 12, 2);
            $table->integer('stock')->default(0);
            $table->integer('weight')->default(500);

            // DETAIL PRODUK
            $table->string('material')->nullable();
            $table->string('color')->nullable();
            $table->string('dimension')->nullable();
            $table->enum('condition', ['new', 'used'])->default('new');

            // MEDIA
            $table->string('thumbnail')->nullable();

            // STATUS & ANALYTICS
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('order_count')->default(0);

            // REVIEW
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->integer('total_reviews')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};