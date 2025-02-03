<?php

namespace App\Filament\Admin\Resources\CroscekTkResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\CroscekTkResource;
use App\Filament\Admin\Resources\CroscekTkResource\Widgets\CroscekSiswaTkWidget;

class ListCroscekTks extends ListRecords
{
    protected static string $resource = CroscekTkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('exportPdf')
                ->label('Export PDF')
                ->color('success')
                ->icon('heroicon-o-document-arrow-down')
                ->url(fn () => route('export.croscek', ['unit' => 'tk']))
                ->openUrlInNewTab(), // Buka di tab baru agar tidak mengganggu tampilan admin
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CroscekSiswaTkWidget::class,
        ];
    }
}
