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
        Schema::create('armas', function (Blueprint $table) {
            $table->id(); // campo id (auto-increment)
            $table->timestamps(); // campos created_at e updated_at
        });
    
        
        // Schema::table('users', function (Blueprint $table) {
        //     $table->foreignId('arma_id')->nullable()->constrained()->onDelete('set null');
        // });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('armas');
    }
};
