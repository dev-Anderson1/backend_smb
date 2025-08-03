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
    Schema::create('algemas', function (Blueprint $table) {
        $table->id();
        $table->string('tipo');
        $table->string('num_serie');
        $table->integer('quantidade');
        $table->timestamps();
    });

    // Schema::table('users', function (Blueprint $table) {
    //     $table->foreignId('algema_id')->nullable()->constrained()->onDelete('set null');
    // });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('algemas');
    }
};
