<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearchPartnersTable extends Migration
{
    public function up()
    {
        Schema::create('research_partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();   // path logo
            $table->string('website')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('research_partners');
    }
}
