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
        Schema::create('anotacoes_foto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('foto_id')->constrained('fotos')->onDelete('cascade');
            $table->decimal('x', 8, 2);
            $table->decimal('y', 8, 2);
            $table->text('texto');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anotacoes_foto');
    }
};
