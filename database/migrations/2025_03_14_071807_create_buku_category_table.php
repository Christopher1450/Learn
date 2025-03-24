<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBukuCategoryTable extends Migration
{
    public function up()
    {
        Schema::create('buku_category', function (Blueprint $table) {
            $table->unsignedBigInteger('id_buku');
            $table->unsignedBigInteger('category_id');
        
            $table->foreign('id_buku')->references('id_buku')->on('buku')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        
            $table->primary(['id_buku', 'category_id']);
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('buku_category');
    }
}
