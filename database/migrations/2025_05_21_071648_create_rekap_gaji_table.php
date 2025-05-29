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
        Schema::create('rekap_gaji', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->double('gaji_pokok')->nullable();
            $table->double('lembur')->nullable();
            $table->double('bonus')->nullable();
            $table->double('total_gaji')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_gaji');
    }
};
