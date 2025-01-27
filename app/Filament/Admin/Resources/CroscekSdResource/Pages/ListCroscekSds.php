<?php

namespace App\Filament\Admin\Resources\CroscekSdResource\Pages;

use App\Filament\Admin\Resources\CroscekSdResource;
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
}
