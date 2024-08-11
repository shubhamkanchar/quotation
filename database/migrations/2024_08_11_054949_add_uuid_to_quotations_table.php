<?php

use App\Models\MakeQuotation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('make_quotations', function (Blueprint $table) {
            if(!Schema::hasColumn('make_quotations', 'uuid') ) {
                $table->uuid('uuid')->unique()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('make_quotations', function (Blueprint $table) {
            if(Schema::hasColumn('make_quotations', 'uuid') ) {
                $table->dropColumn('uuid');
            }
        });
    }
};
