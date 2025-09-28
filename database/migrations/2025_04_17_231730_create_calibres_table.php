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
        Schema::create('calibres', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('medidas');
            $table->timestamps();
        });
    
        // (Se quiser adicionar outra FK aqui no futuro, pode fazer aqui)
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calibres');
    }
};
