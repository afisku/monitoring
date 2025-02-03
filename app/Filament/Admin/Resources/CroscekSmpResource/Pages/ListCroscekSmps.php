<?php

namespace App\Filament\Admin\Resources\CroscekSmpResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\CroscekSmpResource;
use App\Filament\Admin\Resources\CroscekSmpResource\Widgets\CroscekSiswaSmpWidget;

class ListCroscekSmps extends ListRecords
{
    protected static string $resource = CroscekSmpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('exportPdf')
                ->label('Export PDF')
                ->color('success')
                ->icon('heroicon-o-document-arrow-down')
                ->url(fn () => route('export.croscek', ['unit' => 'smp']))
                ->openUrlInNewTab(), // Buka di tab baru agar tidak mengganggu tampilan admin
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CroscekSiswaSmpWidget::class,
        ];
    }
}
