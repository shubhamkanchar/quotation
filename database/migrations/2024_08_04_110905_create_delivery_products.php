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
        Schema::create('delivery_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('delivery_note_id');
            $table->integer('quantity');
            $table->text('description')->nullable();
            $table->tinyInteger('sort_order');
            $table->float('price')->nullable();

            $table->foreign('product_id')->references('id')->on('product_models')->onDelete('cascade');
            $table->foreign('delivery_note_id')->references('id')->on('make_delivery_notes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_products');
    }
};
