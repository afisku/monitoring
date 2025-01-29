<?php

namespace App\Filament\Admin\Resources\CroscekSdResource\Pages;

use Filament\Actions;
use App\Models\TahunAkademik;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Admin\Resources\CroscekSdResource;

class CreateCroscekSd extends CreateRecord
{
    protected static string $resource = CroscekSdResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!auth()->user()->hasRole(['superadmin', 'admin']) && auth()->user()->unit_id != null) {
            $data['unit_id'] = auth()->user()->unit_id;
        }
        // TODO: Tambah pengaturan untuk mengenablekan opsi tahun akademik
        $data['tahun_akademik_id'] = TahunAkademik::firstWhere('status', true)?->id;

        return $data;
    }
}
