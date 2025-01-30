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
        ];
    }
}
