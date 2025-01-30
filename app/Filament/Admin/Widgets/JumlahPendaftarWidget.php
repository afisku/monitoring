<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Siswa;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class JumlahPendaftarWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Target pendaftaran berdasarkan unit sekolah
        $targets = [
            'TKIT' => 40,
            'SDIT' => 54,
            'SMPIT' => 120,
            'SMAIT' => 60,
        ];

        $stats = [];

        foreach ($targets as $unit => $target) {
            // Menghitung jumlah siswa berdasarkan unit
            $currentCount = Siswa::whereHas('unit', function ($query) use ($unit) {
                $query->where('nm_unit', $unit);
            })->count();

            // Hitung persentase capaian
            $percentage = ($currentCount / $target) * 100;
            $percentage = round($percentage, 2); // Dibulatkan dua angka desimal

            // Menentukan warna indikator
            $color = $percentage >= 100 ? 'success' : ($percentage >= 50 ? 'warning' : 'danger');

            // Menambahkan statistik ke dalam array
            $stats[] = Stat::make("Pendaftaran $unit", "$currentCount / $target")
                ->description("Progress: $percentage% dari target")
                ->descriptionIcon('heroicon-o-user-group')
                ->color($color)
                ->chart([$percentage]);
        }

        return $stats;
    }
}
