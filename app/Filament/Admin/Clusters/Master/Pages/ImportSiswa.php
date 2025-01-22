<?php

namespace App\Filament\Admin\Clusters\Master\Pages;


use Filament\Pages\Page;
use App\Imports\SiswaImportProcessor;
use App\Filament\Admin\Clusters\Master;


class ImportSiswa extends Page 
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.clusters.master.pages.import-siswa';

    protected static ?string $cluster = Master::class;

    protected function getHeaderActions(): array
    {
        return [
            \EightyNine\ExcelImport\ExcelImportAction::make()
                ->color("primary")
                // ->use(PegawaiImportProcessor::class),
                // \Filament\Actions\Action::make('download')
                ->label('Download Template')
                ->color('success')
                ->icon('heroicon-m-document-arrow-down')
                // ->url(route('download-template-siswa')) // Route untuk download
                ->openUrlInNewTab(), // Membuka di tab baru (opsional)
        ];
    }
}
