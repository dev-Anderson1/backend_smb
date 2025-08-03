<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Verifica e remove foreign key e coluna, se existirem
            if (Schema::hasColumn('users', 'colete_id')) {
                $table->dropForeign(['colete_id']);
                $table->dropColumn('colete_id');
            }
            if (Schema::hasColumn('users', 'espada_id')) {
                $table->dropForeign(['espada_id']);
                $table->dropColumn('espada_id');
            }
            if (Schema::hasColumn('users', 'algema_id')) {
                $table->dropForeign(['algema_id']);
                $table->dropColumn('algema_id');
            }
            if (Schema::hasColumn('users', 'arma_id')) {
                $table->dropForeign(['arma_id']);
                $table->dropColumn('arma_id');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('colete_id')->nullable()->constrained('coletes');
            $table->foreignId('espada_id')->nullable()->constrained('espadas');
            $table->foreignId('algema_id')->nullable()->constrained('algemas');
            $table->foreignId('arma_id')->nullable()->constrained('armas');
        });
    }
};

