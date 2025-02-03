<?php

namespace App\Filament\Admin\Resources\CroscekSdResource\Pages;

use App\Filament\Admin\Resources\CroscekSdResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCroscekSd extends EditRecord
{
    protected static string $resource = CroscekSdResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
    $record = $this->record->load('unit'); // Pastikan relasi unit diload
    return array_merge($data, ['unit_id' => $record->unit_id]);
}

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
