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
        Schema::create('modelo_armas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('calibre_id');
            $table->string('numero_serie');
            $table->timestamps();
        });
    
        // Adicionar a foreign key separadamente
        Schema::table('modelo_armas', function (Blueprint $table) {
            if (Schema::hasTable('calibres')) {
                $table->foreign('calibre_id')->references('id')->on('calibres')->onDelete('cascade');
            }
            if (Schema::hasTable('armas')) {
                $table->foreignId('arma_id')->nullable()->constrained()->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        // Remover as chaves estrangeiras primeiro (em ordem inversa)
        Schema::table('modelo_armas', function (Blueprint $table) {
            $table->dropForeign(['calibre_id']); // Remover a chave estrangeira de calibre_id
            $table->dropForeign(['arma_id']); // Remover a chave estrangeira de arma_id
        });

        // Remover a tabela modelo_armas
        Schema::dropIfExists('modelo_armas');
    }
    
};
