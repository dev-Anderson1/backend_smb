<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'colete_id')) {
                $table->dropColumn('colete_id');
            }
            if (Schema::hasColumn('users', 'espada_id')) {
                $table->dropColumn('espada_id');
            }
            if (Schema::hasColumn('users', 'algema_id')) {
                $table->dropColumn('algema_id');
            }
            if (Schema::hasColumn('users', 'arma_id')) {
                $table->dropColumn('arma_id');
            }
            if (Schema::hasColumn('users', 'outros_materiais')) {
                $table->dropColumn('outros_materiais');
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
            $table->text('outros_materiais')->nullable();
        });
    }
};
