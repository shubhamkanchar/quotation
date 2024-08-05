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
        Schema::table('make_invoices', function (Blueprint $table) {
            if(!Schema::hasColumn('make_invoices', 'invoice_no')) {
                $table->unsignedBigInteger('invoice_no'); // for pdf
            }

            if(!Schema::hasColumn('make_invoices', 'customer_id')) {
                $table->unsignedBigInteger('customer_id');
            }

            if(!Schema::hasColumn('make_invoices', 'total_amount')) {
                $table->float('total_amount')->nullable();
            }
            if(!Schema::hasColumn('make_invoices', 'paid_amount')) {
                $table->float('paid_amount')->nullable();
            }
            if(!Schema::hasColumn('make_invoices', 'round_off')) {
                $table->boolean('round_off')->nullable();
            }
            if(!Schema::hasColumn('make_invoices', 'invoice_date')) {
                $table->date('invoice_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('make_invoices', function (Blueprint $table) {
            if(Schema::hasColumn('make_invoices', 'invoice_no')) {
                $table->dropColumn('invoice_no');
            }
            if(Schema::hasColumn('make_invoices', 'customer_id')) {
                $table->dropColumn('customer_id');
            }
            if(Schema::hasColumn('make_invoices', 'total_amount')) {
                $table->dropColumn('total_amount');
            }
            if(Schema::hasColumn('make_invoices', 'round_off')) {
                $table->dropColumn('round_off');
            }
            if(Schema::hasColumn('make_invoices', 'invoice_date')) {
                $table->dropColumn('invoice_date');
            }
        });
    }
};
