<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBukuTable extends Migration
{
    public function up()
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->bigIncrements('id_buku')->primary();
            $table->string('judul_buku');
            $table->string('pengarang');
            $table->string('penerbit');
            $table->year('th_terbit');
            // $table->string('category_id');
            $table->integer('stock');
        
            // $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
            }

    public function down()
    {
        Schema::dropIfExists('buku');
    }
}
