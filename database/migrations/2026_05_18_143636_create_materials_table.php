<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->increments('material_id');
            $table->unsignedInteger('outlet_id');
            $table->unsignedInteger('produk_id');
            $table->integer('stock');

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
        Schema::dropIfExists('materials');
    }
};