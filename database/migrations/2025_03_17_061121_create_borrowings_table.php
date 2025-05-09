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
        {
    Schema::create('borrowings', function (Blueprint $table) {
        $table->string('id_borrowing')->primary();
        $table->string('kode_unit')->nullable();
        $table->string('id');
        $table->string('id_buku');
        $table->string('borrower_name')->nullable();
        $table->date('borrow_date');
        $table->date('return_date');
        $table->date('returned_at')->nullable();
        
        // Jaminan
        $table->enum('jenis_jaminan', ['uang', 'barang']);
        $table->integer('jumlah_jaminan')->nullable();
        $table->string('bukti_jaminan')->nullable();
    
        $table->integer('fee')->default(0);
        $table->integer('penalty')->default(0);
        $table->string('bukti_pengembalian')->nullable();
        $table->string('bukti_pembayaran')->nullable();

        $table->integer('pengembalian_jaminan')->nullable();
    
        $table->timestamps();
        });
    }
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowings');
        // Schema::table('borrowings', function (Blueprint $table) {
        //     $table->string('bukti_pengembalian')->nullable(false)->change();
        //     $table->string('bukti_pembayaran')->nullable(false)->change();
        // });
    }
    
};
