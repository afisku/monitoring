<?php

namespace App\Filament\Admin\Resources\CroscekTkResource\Pages;

use App\Filament\Admin\Resources\CroscekTkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\TahunAkademik;

class EditCroscekTk extends EditRecord
{
    protected static string $resource = CroscekTkResource::class;

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
