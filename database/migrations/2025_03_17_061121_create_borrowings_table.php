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
        $table->string('id')->constrained('users')->onDelete('cascade');
        $table->string('id_buku')->constrained('buku')->onDelete('cascade');
        $table->date('borrow_date');
        $table->date('return_date');
        $table->date('returned_at')->nullable();

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
    }
};
