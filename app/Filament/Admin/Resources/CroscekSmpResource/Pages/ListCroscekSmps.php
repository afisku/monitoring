<?php

namespace App\Filament\Admin\Resources\CroscekSmpResource\Pages;

use App\Filament\Admin\Resources\CroscekSmpResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

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
                ->url(fn () => route('export.croscek-smp'))
                ->openUrlInNewTab(), // Buka di tab baru agar tidak mengganggu tampilan admin
        ];
    }
}
