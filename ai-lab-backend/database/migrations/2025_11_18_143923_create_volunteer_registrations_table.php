<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('volunteer_registrations')) {
            Schema::create('volunteer_registrations', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('full_name', 200);
                $table->string('nickname', 100)->nullable();
                $table->string('study_program', 150)->nullable();
                $table->smallInteger('semester')->nullable();
                $table->string('email', 200)->nullable();
                $table->string('phone', 50)->nullable();
                $table->json('areas')->nullable(); 
                $table->text('skills')->nullable();
                $table->text('motivation')->nullable();
                $table->string('availability', 100)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_registrations');
    }
};
