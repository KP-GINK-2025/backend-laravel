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
        Schema::create('jenis_asets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelompok_aset_id')->constrained()->onDelete('cascade');

            $table->string('kode_jenis_aset')->unique();
            $table->string('nama_jenis_aset')->unique();
            $table->string('kode', 50)->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_asets');
    }
};
