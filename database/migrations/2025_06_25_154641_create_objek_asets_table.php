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
        Schema::create('objek_asets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_aset_id')->constrained()->onDelete('cascade');
            $table->integer('kode_objek_aset')->unique();
            $table->string('nama_objek_aset')->unique();
            $table->string('kode', 50)->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('objek_asets');
    }
};
