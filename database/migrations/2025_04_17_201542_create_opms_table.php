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
        Schema::create('opms', function (Blueprint $table) {
            $table->id();
            $table->string('bpm');
            $table->timestamps();
        });
    
        // Schema::table('users', function (Blueprint $table) {
        //     $table->foreignId('opm_id')->nullable()->constrained()->onDelete('set null');
        // });
    }
    

    public function down()
{
    Schema::dropIfExists('opms');
}
};
