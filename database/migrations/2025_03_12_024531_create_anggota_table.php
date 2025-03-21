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
    Schema::create('anggota', function (Blueprint $table) {
        $table->id('id_anggota')->primary();
        $table->string('nama');
        // $table->string('kelas');
        $table->string('no_hp')->unique();
        $table->timestamps();
    });
    }
};
