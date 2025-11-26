<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();

            $table->string('full_name');
            $table->string('role')->nullable();
            $table->string('photo')->nullable();

            $table->text('expertise')->nullable();         // bidang riset
            $table->longText('description')->nullable();   // deskripsi panjang

            // external accounts
            $table->string('linkedin')->nullable();
            $table->string('scholar')->nullable();
            $table->string('researchgate')->nullable();
            $table->string('orcid')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
