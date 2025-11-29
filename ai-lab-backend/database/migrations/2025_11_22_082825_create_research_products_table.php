<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearchProductsTable extends Migration
{
    public function up()
    {
        Schema::create('research_products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();  // simpan URL/path
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('research_products');
    }
}
