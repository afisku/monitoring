<?php

namespace App\Filament\Admin\Resources\SiswaResource\Pages;

use App\Filament\Admin\Resources\SiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Imports\SiswaImportProcessor;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;



class ListSiswas extends ListRecords
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [

            \EightyNine\ExcelImport\ExcelImportAction::make()
            ->slideOver()
            ->color("primary")
            ->use(SiswaImportProcessor::class),

            Actions\CreateAction::make(),
        ];

    }
}
