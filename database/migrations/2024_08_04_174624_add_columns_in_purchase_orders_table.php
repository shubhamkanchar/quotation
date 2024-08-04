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
        Schema::table('make_purchase_orders', function (Blueprint $table) {
            if(!Schema::hasColumn('make_purchase_orders', 'purchase_order_no')) {
                $table->unsignedBigInteger('purchase_order_no'); // for pdf
            }

            if(!Schema::hasColumn('make_purchase_orders', 'customer_id')) {
                $table->unsignedBigInteger('customer_id');
            }

            if(!Schema::hasColumn('make_purchase_orders', 'total_amount')) {
                $table->integer('total_amount')->nullable();
            }
            if(!Schema::hasColumn('make_purchase_orders', 'round_off')) {
                $table->boolean('round_off')->nullable();
            }
            if(!Schema::hasColumn('make_purchase_orders', 'purchase_date')) {
                $table->date('purchase_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('make_purchase_orders', function (Blueprint $table) {
            if(Schema::hasColumn('make_purchase_orders', 'purchase_order_no')) {
                $table->dropColumn('purchase_order_no');
            }
            if(Schema::hasColumn('make_purchase_orders', 'customer_id')) {
                $table->dropColumn('customer_id');
            }
            if(Schema::hasColumn('make_purchase_orders', 'total_amount')) {
                $table->dropColumn('total_amount');
            }
            if(Schema::hasColumn('make_purchase_orders', 'round_off')) {
                $table->dropColumn('round_off');
            }
            if(Schema::hasColumn('make_purchase_orders', 'purchase_date')) {
                $table->dropColumn('purchase_date');
            }
        });
    }
};
