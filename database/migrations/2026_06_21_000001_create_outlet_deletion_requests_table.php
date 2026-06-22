<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outlet_deletion_requests', function (Blueprint $table) {
            $table->increments('outlet_deletion_request_id');
            $table->unsignedInteger('outlet_id')->nullable();
            $table->unsignedInteger('franchise_id');
            $table->unsignedInteger('brand_id');
            $table->string('outlet_name', 100);
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            $table->foreign('outlet_id')
                ->references('outlet_id')
                ->on('outlets')
                ->onUpdate('cascade')
                ->onDelete('set null');

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
        Schema::dropIfExists('outlet_deletion_requests');
    }
};
