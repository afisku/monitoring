<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use Illuminate\Support\Str;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Admin\Resources\UserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->user()->hasRole(['superadmin'])) {
            $data['email_verified_at'] = now();
        }

        $data['username'] = Str::slug($data['username']);

        return $data;
    }
}
