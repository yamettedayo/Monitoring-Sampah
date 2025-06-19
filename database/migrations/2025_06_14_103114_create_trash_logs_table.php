<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trash_logs', function (Blueprint $table) {
            $table->id();
            $table->string('lokasi');
            $table->integer('volume');
            $table->timestamp('waktu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trash_logs');
    }
};
