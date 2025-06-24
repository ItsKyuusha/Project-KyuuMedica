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
        Schema::create('pasiens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();  // Relasi ke user
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('nama');  // Nama pasien (terpisah dari nama di users)
            $table->string('alamat');
            $table->string('no_ktp', 25);
            $table->string('no_hp', 60);
            $table->string('no_rm', 25);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
