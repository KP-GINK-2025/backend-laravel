<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kelompok_asets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('akun_aset_id')->constrained()->onDelete('cascade');
            $table->integer('kode_kelompok_aset')->unique();
            $table->string('nama_kelompok_aset')->unique();
            $table->string('kode', 50)->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok_asets');
    }
};
