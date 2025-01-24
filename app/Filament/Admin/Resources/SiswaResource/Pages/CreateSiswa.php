<?php

namespace App\Filament\Admin\Resources\SiswaResource\Pages;

use Filament\Actions;
use App\Models\TahunAkademik;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Admin\Resources\SiswaResource;

class CreateSiswa extends CreateRecord
{
    protected static string $resource = SiswaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // if (!auth()->user()->hasRole(['superadmin', 'admin']) && auth()->user()->jurusan_id != null) {
        //     $data['jurusan_id'] = auth()->user()->jurusan_id;
        // }
        // TODO: Tambah pengaturan untuk mengenablekan opsi tahun akademik
        $data['tahun_akademik_id'] = TahunAkademik::firstWhere('status', true)?->id;

        return $data;
    }

}
