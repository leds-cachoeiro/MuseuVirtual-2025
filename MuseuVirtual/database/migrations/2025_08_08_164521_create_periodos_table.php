<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periodos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->unsignedBigInteger('era_id');
            $table->timestamps();

            $table->foreign('era_id')->references('id')->on('eras')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periodos');
    }
};
