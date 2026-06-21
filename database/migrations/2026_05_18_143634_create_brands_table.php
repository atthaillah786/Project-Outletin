<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('brands', function (Blueprint $table) {
        $table->id('brand_id'); // Primary key
        $table->string('brand_name');
        $table->text('description')->nullable();
        $table->string('logo_path')->nullable(); // <-- GUNANYA: Menyimpan jalur/path teks file gambar di storage
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};