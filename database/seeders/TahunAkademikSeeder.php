<?php

namespace Database\Seeders;

use App\Models\TahunAkademik;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TahunAkademikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahunAkademik = [
            ['th_akademik' => '2024/2025', 'status' => false],
            ['th_akademik' => '2025/2026', 'status' => false],
            ['th_akademik' => '2026/2027', 'status' => false],
            ['th_akademik' => '2027/2028', 'status' => false],
            ['th_akademik' => '2028/2029', 'status' => false],
        ];
        TahunAkademik::insert($tahunAkademik);
    }
}
