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
        Schema::create('carregadores', function (Blueprint $table) {
            $table->id();
            $table->integer('capacidade');
            $table->integer('quantidade');
           // $table->foreignId('arma_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

           Schema::table('carregadores', function (Blueprint $table) {
            $table->foreignId('arma_id')->nullable()->constrained()->onDelete('set null');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carregadors');
    }
};
