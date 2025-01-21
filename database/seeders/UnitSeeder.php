<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $units = [
            ['nm_unit' => 'TKIT', 'kode_unit' => 'tkit'],
            ['nm_unit' => 'SDIT', 'kode_unit' => 'sdit'],
            ['nm_unit' => 'SMPIT', 'kode_unit' => 'smpit'],
            ['nm_unit' => 'SMAIT', 'kode_unit' => 'smait'],
        ];
        foreach ($units as $unit) {
            Unit::create([
                'nm_unit' => $unit['nm_unit'],
                'kode_unit' => $unit['kode_unit'],
            ]);
        }
    
    }
}
