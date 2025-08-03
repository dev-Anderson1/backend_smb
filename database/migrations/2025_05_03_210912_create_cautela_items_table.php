<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCautelaItemsTable extends Migration
{
    public function up(): void
    {
        Schema::create('cautela_items', function (Blueprint $table) {
            $table->id();

            // Referência à cautela
            $table->foreignId('cautela_id')->constrained('cautelas')->onDelete('cascade');

            // Referências aos tipos de materiais, todos opcionais
            $table->foreignId('arma_id')->nullable()->constrained('armas')->onDelete('set null');
            $table->foreignId('colete_id')->nullable()->constrained('coletes')->onDelete('set null');
            $table->foreignId('espada_id')->nullable()->constrained('espadas')->onDelete('set null');
            $table->foreignId('algema_id')->nullable()->constrained('algemas')->onDelete('set null');

            // Outros materiais (texto livre)
            $table->text('outros_materiais')->nullable();

            // Quantidade (caso aplicável)
            $table->integer('quantidade')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cautela_items');
    }
}
