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
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CroscekSiswaTkWidget::class,
        ];
    }
}
