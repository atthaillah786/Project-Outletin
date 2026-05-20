<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('transaction_id');
            $table->unsignedInteger('outlet_id');
            $table->unsignedInteger('karyawan_id');
            $table->enum('type', ['income', 'expense']);
            $table->decimal('amount', 12, 2);
            $table->text('description')->nullable();
            $table->date('transaction_date');

            $table->foreign('outlet_id')
                ->references('outlet_id')
                ->on('outlets')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('karyawan_id')
                ->references('user_id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};