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
        Schema::table('other_charges', function (Blueprint $table) {
            $table->float('gst_amount')->nullable()->change();
        });
        
        Schema::table('make_purchase_orders', function (Blueprint $table) {
            $table->float('total_amount')->change()->nullable();
        });

        Schema::table('make_quotations', function (Blueprint $table) {
            $table->float('total_amount')->change()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('other_charges', function (Blueprint $table) {
            $table->integer('gst_amount')->nullable()->change();
        });
        
        Schema::table('make_purchase_orders', function (Blueprint $table) {
            $table->integer('total_amount')->change()->nullable();
        });

        Schema::table('make_quotations', function (Blueprint $table) {
            $table->integer('total_amount')->change()->nullable();
        });
    }
};
