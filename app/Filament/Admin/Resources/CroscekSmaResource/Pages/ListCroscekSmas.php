<?php

namespace App\Filament\Admin\Resources\CroscekSmaResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\CroscekSmaResource;
use App\Filament\Admin\Resources\CroscekSmaResource\Widgets\CroscekSiswaSmaWidget;

class ListCroscekSmas extends ListRecords
{
    protected static string $resource = CroscekSmaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('exportPdf')
                ->label('Export PDF')
                ->color('success')
                ->icon('heroicon-o-document-arrow-down')
                ->url(fn () => route('export.croscek', ['unit' => 'sma']))
                ->openUrlInNewTab(), // Buka di tab baru agar tidak mengganggu tampilan admin
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CroscekSiswaSmaWidget::class,
        ];
    }
}
