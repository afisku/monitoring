<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatusCasisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('status_casis')->insert([
            [
                'nm_status_casis' => 'BELUM TEST',
                'warna' => '#ffcc00', // Kuning
                'keterangan' => 'Calon siswa belum melakukan tes.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nm_status_casis' => 'SUDAH TEST',
                'warna' => '#4caf50', // Hijau
                'keterangan' => 'Calon siswa telah melakukan tes.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nm_status_casis' => 'LUNAS',
                'warna' => '#2196f3', // Biru
                'keterangan' => 'Calon siswa telah melunasi biaya.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nm_status_casis' => 'MENGUNDURKAN DIRI',
                'warna' => '#f44336', // Merah
                'keterangan' => 'Calon siswa mengundurkan diri.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
