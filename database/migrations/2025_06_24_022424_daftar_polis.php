<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('daftar_polis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pasien')->nullable()->constrained('pasiens')->nullOnDelete();
            $table->foreignId('id_jadwal')->constrained('jadwal_periksas')->onDelete('cascade');
            $table->text('keluhan')->nullable();

            // Kolom snapshot untuk menyimpan data pasien saat hapus
            $table->string('nama_pasien')->nullable();
            $table->string('no_rm')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daftar_polis', function (Blueprint $table) {
            $table->dropColumn(['nama_pasien', 'no_rm']);
            $table->unsignedBigInteger('id_pasien')->nullable(false)->change(); // kembalikan jadi wajib
        });
    }
};
