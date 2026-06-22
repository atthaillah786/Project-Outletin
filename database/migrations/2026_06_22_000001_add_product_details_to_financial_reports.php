<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('financial_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('financial_reports', 'product_details')) {
                $table->json('product_details')->nullable()->after('total_items');
            }
        });
    }

    public function down()
    {
        Schema::table('financial_reports', function (Blueprint $table) {
            if (Schema::hasColumn('financial_reports', 'product_details')) {
                $table->dropColumn('product_details');
            }
        });
    }
};
