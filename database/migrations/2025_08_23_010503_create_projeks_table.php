<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projeks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_projek');
            $table->string('email');
            $table->date('tanggal_projek');
            $table->date('kadaluwarsa_projek');
            $table->string('file_koordinat')->nullable();
            $table->string('nama_jalur')->nullable();
            $table->json('line')->nullable();
            $table->json('markers')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projeks');
    }
};
