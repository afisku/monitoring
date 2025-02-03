<?php

namespace App\Filament\Admin\Resources\CroscekSdResource\Pages;

use App\Filament\Admin\Resources\CroscekSdResource;
use App\Filament\Admin\Resources\CroscekSdResource\Widgets\CroscekSiswaSdWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCroscekSds extends ListRecords
{
    protected static string $resource = CroscekSdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('exportPdf')
                ->label('Export PDF')
                ->color('success')
                ->icon('heroicon-o-document-arrow-down')
                ->url(fn () => route('export.croscek', ['unit' => 'sd']))
                ->openUrlInNewTab(), // Buka di tab baru agar tidak mengganggu tampilan admin
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CroscekSiswaSdWidget::class,
        ];
    }

}
