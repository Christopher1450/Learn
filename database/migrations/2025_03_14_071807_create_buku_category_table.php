<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBukuCategoryTable extends Migration
{
    public function up()
    {
        Schema::create('buku_category', function (Blueprint $table) {
            $table->integer('id_buku')->constrained('buku')->onDelete('cascade');
            $table->integer('category_id')->constrained('categories')->onDelete('cascade');

            $table->primary(['id_buku', 'category_id']); // Composite primary key
        });
    }

    public function down()
    {
        Schema::dropIfExists('buku_category');
    }
}
