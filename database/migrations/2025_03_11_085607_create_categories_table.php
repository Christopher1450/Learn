<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
            Schema::create('categories', function (Blueprint $table) {
                    $table->bigIncrements('id')->primary();
                    $table->string('name')->unique();
                });
            }
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};