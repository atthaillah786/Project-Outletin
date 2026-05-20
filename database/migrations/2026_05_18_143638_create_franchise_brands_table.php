<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('franchise_brands', function (Blueprint $table) {
            $table->increments('franchise_brands_id');
            $table->unsignedInteger('franchise_id');
            $table->unsignedInteger('brand_id');
            $table->enum('status', ['pending', 'approved', 'rejected']);

            $table->foreign('franchise_id')
                ->references('user_id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('brand_id')
                ->references('brand_id')
                ->on('brands')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('franchise_brands');
    }
};