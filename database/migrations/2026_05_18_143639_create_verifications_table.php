<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verifications', function (Blueprint $table) {
            $table->increments('verification_id');
            $table->unsignedInteger('superadmin_id');
            $table->enum('status', ['approved', 'rejected']);
            $table->timestamp('verified_at')->nullable()->useCurrent();

            $table->foreign('superadmin_id')
                ->references('user_id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifications');
    }
};