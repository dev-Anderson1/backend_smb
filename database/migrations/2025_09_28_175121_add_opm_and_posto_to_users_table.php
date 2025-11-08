<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'opm_id')) {
                $table->unsignedBigInteger('opm_id')->nullable()->after('is_admin');
                // Se já existirem as tabelas, você pode ativar a FK:
                // $table->foreign('opm_id')->references('id')->on('opms')->onDelete('set null');
            }
            if (!Schema::hasColumn('users', 'posto_graduacoes_id')) {
                $table->unsignedBigInteger('posto_graduacoes_id')->nullable()->after('opm_id');
                // $table->foreign('posto_graduacoes_id')->references('id')->on('posto_graduacoes')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Se criou FK, drope antes:
            // $table->dropForeign(['opm_id']);
            // $table->dropForeign(['posto_graduacoes_id']);
            $table->dropColumn(['opm_id','posto_graduacoes_id']);
        });
    }
};

