<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_reports', function (Blueprint $table) {
            $table->increments('financial_id');
            $table->unsignedInteger('outlet_id');
            $table->date('report_date');
            $table->decimal('total_income', 12, 2);
            $table->decimal('total_expense', 12, 2);
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->foreign('outlet_id')
                ->references('outlet_id')
                ->on('outlets')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_reports');
    }
};