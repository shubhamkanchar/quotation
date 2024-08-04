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
        Schema::create('purchase_order_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('purchase_order_id');
            $table->integer('quantity');
            $table->text('description')->nullable();
            $table->tinyInteger('sort_order');
            $table->float('price');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('product_models')->onDelete('cascade');
            $table->foreign('purchase_order_id')->references('id')->on('make_purchase_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_products');
    }
};
