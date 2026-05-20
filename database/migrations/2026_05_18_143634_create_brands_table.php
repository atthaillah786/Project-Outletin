<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->increments('brand_id');
            $table->unsignedInteger('franchisor_id');
            $table->string('brand_name', 100);
            $table->text('description')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->foreign('franchisor_id')
                ->references('user_id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};