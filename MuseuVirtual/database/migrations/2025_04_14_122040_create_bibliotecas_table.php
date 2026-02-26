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
        Schema::create('bibliotecas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId("usuarioId")->constrained("users");
            $table->json("jazidasSalvas")->nullable();
            $table->json("rochasSalvas")->nullable();
            $table->json("mineraisSalvos")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bibliotecas');
    }
};