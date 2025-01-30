<?php

namespace App\Filament\Admin\Widgets;

use App\Models\CroscekSd;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class CroscekSdWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Data Siswa', CroscekSd::count())
            ->color('success')
            ->description('cek data siswa')
            ->descriptionIcon('heroicon-o-academic-cap')
        ];
    }
}
