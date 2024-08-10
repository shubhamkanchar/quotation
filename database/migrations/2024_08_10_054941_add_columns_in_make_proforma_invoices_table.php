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
        Schema::table('make_proforma_invoices', function (Blueprint $table) {
            if(!Schema::hasColumn('make_proforma_invoices', 'proforma_invoice_no')) {
                $table->unsignedBigInteger('proforma_invoice_no'); // for pdf
            }

            if(!Schema::hasColumn('make_proforma_invoices', 'po_no')) {
                $table->string('po_no')->nullable();
            }

            if(!Schema::hasColumn('make_proforma_invoices', 'customer_id')) {
                $table->unsignedBigInteger('customer_id');
            }

            if(!Schema::hasColumn('make_proforma_invoices', 'total_amount')) {
                $table->float('total_amount')->nullable();
            }

            if(!Schema::hasColumn('make_proforma_invoices', 'paid_amount')) {
                $table->float('paid_amount')->nullable();
            }

            if(!Schema::hasColumn('make_proforma_invoices', 'balance_due')) {
                $table->float('balance_due')->nullable();
            }

            if(!Schema::hasColumn('make_proforma_invoices', 'round_off')) {
                $table->boolean('round_off')->nullable();
            }

            if(!Schema::hasColumn('make_proforma_invoices', 'proforma_invoice_date')) {
                $table->date('proforma_invoice_date');
            }
            
            if(!Schema::hasColumn('make_proforma_invoices', 'due_date')) {
                $table->date('due_date')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('make_proforma_invoices', function (Blueprint $table) {
            if(Schema::hasColumn('make_proforma_invoices', 'proforma_invoice_no')) {
                $table->dropColumn('proforma_invoice_no');
            }
            if(Schema::hasColumn('make_proforma_invoices', 'po_no')) {
                $table->dropColumn('po_no');
            }
            if(Schema::hasColumn('make_proforma_invoices', 'customer_id')) {
                $table->dropColumn('customer_id');
            }
            if(Schema::hasColumn('make_proforma_invoices', 'total_amount')) {
                $table->dropColumn('total_amount');
            }
            if(Schema::hasColumn('make_proforma_invoices', 'balance_due')) {
                $table->dropColumn('balance_due');
            }
            if(Schema::hasColumn('make_proforma_invoices', 'round_off')) {
                $table->dropColumn('round_off');
            }
            if(Schema::hasColumn('make_proforma_invoices', 'proforma_invoice_date')) {
                $table->dropColumn('proforma_invoice_date');
            }
            if(Schema::hasColumn('make_proforma_invoices', 'due_date')) {
                $table->dropColumn('due_date');
            }
        });
    }
};
