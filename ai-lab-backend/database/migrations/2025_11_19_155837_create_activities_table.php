<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('label', 255)->nullable(); // contoh: "Chennai – R&D"
            $table->text('short_description')->nullable();
            $table->longText('full_description')->nullable();
            $table->date('published_at')->nullable();
            $table->string('thumbnail_image')->nullable(); // untuk card
            $table->string('banner_image')->nullable();    // untuk detail
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
