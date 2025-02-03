<?php

namespace App\Filament\Admin\Clusters\Master\Resources\DivisiResource\Pages;

use App\Filament\Admin\Clusters\Master\Resources\DivisiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDivisi extends EditRecord
{
    protected static string $resource = DivisiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
