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
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CroscekSiswaSdWidget::class,
        ];
    }

}
