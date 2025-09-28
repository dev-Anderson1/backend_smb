<?php

namespace Database\Seeders;

use App\Models\Algema;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AlgemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Algema::create([
                'tipo' => 'Algema tipo ' . $i,
                'num_serie' => 'ALG-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'quantidade' => rand(1, 5),
            ]);
        }
    }
    
}


