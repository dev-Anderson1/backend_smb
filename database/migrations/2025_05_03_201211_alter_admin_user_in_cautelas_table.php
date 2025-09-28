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
    Schema::table('cautelas', function (Blueprint $table) {
        // Primeiro remove as foreign keys existentes se houverem
        $table->dropForeign(['admin_id']);
        $table->dropForeign(['user_id']);

        // Agora recria corretamente
        $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}

    public function down()
    {
        Schema::table('cautelas', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropForeign(['user_id']);
        });
    }
};
