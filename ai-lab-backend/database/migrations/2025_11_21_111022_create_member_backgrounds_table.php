<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::create('member_backgrounds', function (Blueprint $table) {
        $table->id();
        $table->foreignId('member_id')->constrained()->onDelete('cascade');
        $table->string('institute');
        $table->string('academic_title');
        $table->string('year');
        $table->string('degree');
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('member_backgrounds');
    }
};
