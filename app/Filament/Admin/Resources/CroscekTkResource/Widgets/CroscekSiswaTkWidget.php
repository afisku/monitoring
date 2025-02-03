<?php

namespace App\Filament\Admin\Resources\CroscekTkResource\Widgets;

use App\Models\CroscekTk;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class CroscekSiswaTkWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalSiswa = CroscekTk::count(); // Total siswa dalam CroscekSD
        $anakGtkCount = CroscekTk::where('anak_gtk', 'YA')->count(); // Hitung jumlah anak GTK
        $permintaanCount = CroscekTk::whereNotNull('permintaan')->count(); // Hitung jumlah permintaan
        $noteCount = CroscekTk::whereNotNull('note')->count(); // Hitung jumlah permintaan

        return [
            Stat::make('Data Siswa', $totalSiswa)
                ->color('info')
                ->description('Total siswa dalam Croscek TK')
                ->descriptionIcon('heroicon-o-academic-cap'),

            Stat::make('Anak GTK', $anakGtkCount)
                ->color($anakGtkCount > 0 ? 'success' : 'danger') // Warna hijau jika ada anak GTK, merah jika nol
                ->description('Jumlah siswa yang merupakan anak GTK')
                ->descriptionIcon('heroicon-o-user-group'),

            Stat::make('Request', $permintaanCount)
                ->color($permintaanCount > 0 ? 'success' : 'danger') // Warna hijau jika ada anak GTK, merah jika nol
                ->description('Permintaan Ortu Casis')
                ->descriptionIcon('heroicon-o-pencil-square'),

            Stat::make('Note', $noteCount)
                ->color($noteCount > 0 ? 'success' : 'danger') // Warna hijau jika ada anak GTK, merah jika nol
                ->description('Note Ortu Casis')
                ->descriptionIcon('heroicon-o-pencil-square'),
        ];
    }
}
