<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSirkulasiTable extends Migration
{
    public function up()
    {
        Schema::create('sirkulasi', function (Blueprint $table) {
            $table->id('id_sirkulasi')->primary();
            $table->string('id_anggota')->constrained('anggota', 'id_anggota')->onDelete('cascade');
            $table->date('tgl_pinjam');
            $table->date('tgl_kembali')->nullable();
            $table->enum('status', ['PIN', 'KEM'])->default('PIN'); 
        });        
    }
    public function down()
    {
        Schema::dropIfExists('sirkulasi');
    }
}
