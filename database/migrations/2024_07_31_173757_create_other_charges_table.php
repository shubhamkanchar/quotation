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
        Schema::create('other_charges', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->float('amount');
            $table->boolean('is_taxable');
            $table->tinyInteger('gst_percentage')->nullable();
            $table->integer('gst_amount')->nullable();
            $table->morphs('chargeable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('other_charges');
    }
};
