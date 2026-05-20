<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outlets', function (Blueprint $table) {
            $table->increments('outlet_id');
            $table->unsignedInteger('franchise_id');
            $table->unsignedInteger('brand_id');
            $table->string('outlet_name', 100);
            $table->text('address')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected']);
            $table->timestamp('created_at')->nullable()->useCurrent();

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
        Schema::dropIfExists('outlets');
    }
};