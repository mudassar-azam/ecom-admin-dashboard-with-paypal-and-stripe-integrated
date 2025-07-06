<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('variations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('attribute_id')->nullable();
            $table->unsignedBigInteger('attribute_value_id')->nullable();
            $table->string('name')->nullable();
            $table->string('sku')->nullable();
            $table->text('description')->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->string('stock_status')->nullable();
            $table->text('gallery_images')->nullable();
            $table->timestamps();
            
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
            $table->foreign('attribute_value_id')->references('id')->on('attribute_values')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variations');
    }
};
