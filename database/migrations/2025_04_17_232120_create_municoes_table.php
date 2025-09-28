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
        Schema::create('municoes', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->foreignId('calibre_id')->constrained()->onDelete('cascade');
            $table->foreignId('arma_id')->constrained()->onDelete('cascade');
            $table->integer('quantidade');
            $table->timestamps();
        });
    }
    


    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('municoes', function (Blueprint $table) {
        $table->dropForeign(['arma_id']);
        $table->dropColumn('arma_id');
    });
}
};
