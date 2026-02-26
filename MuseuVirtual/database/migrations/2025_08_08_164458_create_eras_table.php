<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eras', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->text('imagem')->nullable();
            $table->unsignedBigInteger('eon_id');
            $table->timestamps();

            $table->foreign('eon_id')->references('id')->on('eons')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eras');
    }
};
