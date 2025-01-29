<?php

namespace App\Filament\Admin\Resources\App\Filament\Admin\Resources\CroscekSdResource\Widgets;

use App\Models\CroscekSd;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class CroscekSdStats extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Casis', CroscekSd::count())
                ->description('Jumlah semua calon siswa')
                ->color('primary'),

            Card::make('Casis Diterima', CroscekSd::where('biodata', 'ACC')->count())
                ->description('Sudah diterima')
                ->color('success'),

            Card::make('Belum Diisi', CroscekSd::where('biodata', 'BELUM DIISI')->count())
                ->description('Belum mengisi biodata')
                ->color('danger'),
        ];
    }
}