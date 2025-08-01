<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trash_volumes', function (Blueprint $table) {
            $table->id();
            $table->string('lokasi');
            $table->integer('volume'); // persen, misal 0 - 100%
            $table->timestamp('waktu')->useCurrent();
            $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trash_volumes');
    }
};
