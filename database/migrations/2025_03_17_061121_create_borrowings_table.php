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
        $table->string('id')->constrained('users')->onDelete('cascade');
        $table->string('id_buku')->constrained('buku')->onDelete('cascade');
        $table->string('borrower_name')->nullable();
        $table->date('borrow_date');
        $table->date('return_date');
        $table->date('returned_at')->nullable();
        $table->integer('fee')->default(0);
        $table->integer('penalty')->default(0);
        $table->string('bukti_pengembalian')->nullable();
        $table->string('bukti_pembayaran')->nullable();
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
