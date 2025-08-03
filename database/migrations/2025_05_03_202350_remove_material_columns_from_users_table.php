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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'colete_id',
                'espada_id',
                'algema_id',
                'arma_id',
                'outros_materiais',
            ]);
        });
    }
    
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('colete_id')->nullable()->constrained('coletes');
            $table->foreignId('espada_id')->nullable()->constrained('espadas');
            $table->foreignId('algema_id')->nullable()->constrained('algemas');
            $table->foreignId('arma_id')->nullable()->constrained('armas');
            $table->text('outros_materiais')->nullable();
        });
    }
    
};
