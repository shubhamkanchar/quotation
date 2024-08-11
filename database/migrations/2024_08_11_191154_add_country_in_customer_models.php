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
        Schema::table('customer_models', function (Blueprint $table) {
            if (!Schema::hasColumn('customer_models', 'country')) {
                $table->string('country')->nullable()->before('state');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_models', function (Blueprint $table) {
            if (Schema::hasColumn('customer_models', 'country')) {
                $table->dropColumn('country');
            }
        });
    }
};
