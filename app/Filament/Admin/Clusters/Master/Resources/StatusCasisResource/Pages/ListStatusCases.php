<?php

namespace App\Filament\Admin\Clusters\Master\Resources\StatusCasisResource\Pages;

use App\Filament\Admin\Clusters\Master\Resources\StatusCasisResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStatusCases extends ListRecords
{
    protected static string $resource = StatusCasisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
