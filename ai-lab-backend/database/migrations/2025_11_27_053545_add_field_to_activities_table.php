<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('activities', function (Blueprint $table) {
        if (!Schema::hasColumn('activities', 'document_link')) {
            $table->string('document_link')->nullable()->after('short_description');
        }
    });
}

public function down()
{
    Schema::table('activities', function (Blueprint $table) {
        if (Schema::hasColumn('activities', 'document_link')) {
            $table->dropColumn('document_link');
        }
    });
}


};
