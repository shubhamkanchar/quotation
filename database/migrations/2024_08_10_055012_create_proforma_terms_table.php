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
        Schema::create('proforma_terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proforma_invoice_id');
            $table->unsignedBigInteger('term_id');
            $table->timestamps();
            $table->foreign('proforma_invoice_id')->references('id')->on('make_proforma_invoices')->onDelete('cascade');
            $table->foreign('term_id')->references('id')->on('terms_models')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proforma_terms');
    }
};
