<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('book_units', function (Blueprint $table) {
            $table->id();
            $table->string('kode_unit')->unique();
            $table->string('id_buku');
            $table->string('status')->default('available'); // available | borrowed | lost
            $table->string('barcode_path')->nullable();


            $table->foreign('id_buku')->references('id_buku')->on('buku')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_units');
    }
};
