<?php

namespace App\Filament\Admin\Clusters\Master\Resources\StatusCasisResource\Pages;

use App\Filament\Admin\Clusters\Master\Resources\StatusCasisResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStatusCasis extends CreateRecord
{
    protected static string $resource = StatusCasisResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
