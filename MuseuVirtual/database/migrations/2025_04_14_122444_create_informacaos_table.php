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
        Schema::create('informacaos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('fotoId')->constrained("fotos");
            $table->integer("informacao");
            $table->float("coordenadaX");
            $table->float("coordenadaY");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informacaos');
    }
};
