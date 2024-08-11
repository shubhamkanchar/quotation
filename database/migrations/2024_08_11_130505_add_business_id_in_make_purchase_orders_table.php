<?php

use App\Models\MakePurchaseOrder;
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
            if (!Schema::hasColumn('make_purchase_orders', 'created_by')) {
                $table->unsignedBigInteger('created_by')->after('id');
            }
            if (!Schema::hasColumn('make_purchase_orders', 'business_id')) {
                $table->unsignedBigInteger('business_id')->after('created_by');
            }
            if(!Schema::hasColumn('make_purchase_orders', 'uuid') ) {
                $table->uuid('uuid')->unique()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('make_purchase_orders', function (Blueprint $table) {
            if (Schema::hasColumn('make_proforma_invoices', 'created_by')) {
                $table->dropColumn('created_by');
            }
            if (Schema::hasColumn('make_proforma_invoices', 'business_id')) {
                $table->dropColumn('business_id');
            }
            if(Schema::hasColumn('make_proforma_invoices', 'uuid') ) {
                $table->dropColumn('uuid');
            }
        });
    }
};
