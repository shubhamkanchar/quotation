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
        Schema::table('make_delivery_notes', function (Blueprint $table) {
            if(!Schema::hasColumn('make_delivery_notes', 'order_no')) {
                $table->unsignedBigInteger('order_no'); // for pdf
            }

            if(!Schema::hasColumn('make_delivery_notes', 'customer_id')) {
                $table->unsignedBigInteger('customer_id');
            }

            if(!Schema::hasColumn('make_delivery_notes', 'delivery_date')) {
                $table->date('delivery_date');
            }

            if(!Schema::hasColumn('make_delivery_notes', 'reference_no')) {
                $table->string('reference_no')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('make_delivery_notes', function (Blueprint $table) {
            if(Schema::hasColumn('make_delivery_notes', 'order_no')) {
                $table->dropColumn('order_no');
            }

            if(Schema::hasColumn('make_delivery_notes', 'customer_id')) {
                $table->dropColumn('customer_id');
            }

            if(Schema::hasColumn('make_delivery_notes', 'delivery_date')) {
                $table->dropColumn('delivery_date');
            }

            if(Schema::hasColumn('make_delivery_notes', 'reference_no')) {
                $table->dropColumn('reference_no');
            }
        });
    }
};
