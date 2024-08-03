<?php

use App\Models\MakeQuotation;
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
        Schema::table('make_quotations', function (Blueprint $table) {
            // MakeQuotation::WhereNotNull('id')->delete();
            if(!Schema::hasColumn('make_quotations', 'quotation_no')) {
                $table->unsignedBigInteger('quotation_no'); // for pdf
            }

            if(!Schema::hasColumn('make_quotations', 'customer_id')) {
                $table->unsignedBigInteger('customer_id');
            }

            if(!Schema::hasColumn('make_quotations', 'total_amount')) {
                $table->integer('total_amount')->nullable();
            }
            if(!Schema::hasColumn('make_quotations', 'round_off')) {
                $table->boolean('round_off')->nullable();
            }
            if(!Schema::hasColumn('make_quotations', 'quotation_date')) {
                $table->date('quotation_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('make_quotations', function (Blueprint $table) {
            if(Schema::hasColumn('make_quotations', 'quotation_no')) {
                $table->dropColumn('quotation_no');
            }
            if(Schema::hasColumn('make_quotations', 'customer_id')) {
                $table->dropColumn('customer_id');
            }
            if(Schema::hasColumn('make_quotations', 'total_amount')) {
                $table->dropColumn('total_amount');
            }
            if(Schema::hasColumn('make_quotations', 'round_off')) {
                $table->dropColumn('round_off');
            }
            if(Schema::hasColumn('make_quotations', 'quotation_date')) {
                $table->dropColumn('quotation_date');
            }
        });
    }
};
