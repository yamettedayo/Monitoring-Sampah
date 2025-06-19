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
        Schema::table('trash_volumes', function (Blueprint $table) {
            $table->string('lokasi')->after('id');
        });
    }

    public function down()
    {
        Schema::table('trash_volumes', function (Blueprint $table) {
            $table->dropColumn('lokasi');
        });
    }

};
