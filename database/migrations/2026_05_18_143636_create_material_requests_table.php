<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_requests', function (Blueprint $table) {
            $table->increments('material_request_id');
            $table->unsignedInteger('franchise_id');
            $table->unsignedInteger('outlet_id');
            $table->unsignedInteger('produk_id');
            $table->integer('quantity_requested');
            $table->enum('status', ['pending', 'approved', 'shipped', 'received', 'rejected']);
            $table->timestamp('request_date')->nullable()->useCurrent();

            $table->foreign('franchise_id')
                ->references('user_id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('outlet_id')
                ->references('outlet_id')
                ->on('outlets')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('produk_id')
                ->references('produk_id')
                ->on('produk')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_requests');
    }
};