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
        Schema::table('product_models', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at'); // Adds the deleted_at column
        });

        Schema::table('customer_models', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at'); // Adds the deleted_at column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_models', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Removes the deleted_at column
        });

        Schema::table('customer_models', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Removes the deleted_at column
        });
    }
};
