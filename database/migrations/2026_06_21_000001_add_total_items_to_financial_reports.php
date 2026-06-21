<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('financial_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('financial_reports', 'total_items')) {
                $table->integer('total_items')->default(0)->after('report_date');
            }
        });
    }

    public function down()
    {
        Schema::table('financial_reports', function (Blueprint $table) {
            if (Schema::hasColumn('financial_reports', 'total_items')) {
                $table->dropColumn('total_items');
            }
        });
    }
};
