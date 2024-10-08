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
        Schema::create('proforma_invoice_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('proforma_invoice_id');
            $table->integer('quantity');
            $table->text('description')->nullable();
            $table->tinyInteger('sort_order');
            $table->float('price');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('product_models')->onDelete('cascade');
            $table->foreign('proforma_invoice_id')->references('id')->on('make_proforma_invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proforma_invoice_products');
    }
};
