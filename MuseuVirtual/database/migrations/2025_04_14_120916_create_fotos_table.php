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
        Schema::create('fotos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId("idRocha")->nullable()->constrained("rochas")->onDelete('cascade');
            $table->foreignId("idMineral")->nullable()->constrained("minerals")->onDelete('cascade');
            $table->foreignId("idJazida")->nullable()->constrained("jazidas")->onDelete('cascade');
            $table->boolean("capa");
            $table->string('caminho');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fotos');
    }
};

/** 
*    public function foreignIdFor($model, $column = null)
*/
