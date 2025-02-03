<?php

namespace App\Filament\Admin\Resources\SiswaResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImportProcessor;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\SiswaResource;



class ListSiswas extends ListRecords
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [

            \EightyNine\ExcelImport\ExcelImportAction::make()
            ->slideOver()
            ->color("success")
            ->use(SiswaImportProcessor::class),

            Action::make('download-template') // Action untuk mendownload template
                ->label('Download Template')
                ->icon('heroicon-o-document-arrow-down')
                ->color('warning')
                ->action(fn() => $this->downloadTemplate()),

            Actions\CreateAction::make(),
        ];

    }

    protected function downloadTemplate()
    {
        // Path ke template di storage (sesuaikan dengan lokasi file Anda)
        $templatePath = 'templates/siswa_template.xlsx';

        // Pastikan file template ada
        if (!Storage::exists($templatePath)) {
            return Notification::make()
                ->title('Template Tidak Ditemukan')
                ->body('File template tidak ditemukan di server.')
                ->danger()
                ->send();
        }

        // Kirim file ke pengguna
        return response()->download(Storage::path($templatePath), 'template_siswa.xlsx');
    }
}
