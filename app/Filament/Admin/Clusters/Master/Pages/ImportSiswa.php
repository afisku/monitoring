<?php

namespace App\Filament\Admin\Clusters\Master\Pages;

use Filament\Pages\Page;
use Illuminate\Http\Request;
use App\Imports\SiswaImportProcessor;
use App\Filament\Admin\Clusters\Master;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use EightyEight\FilamentExcel\Actions\Tables\ImportAction;

class ImportSiswa extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-on-square';
    protected static ?string $navigationLabel = 'Impor Siswa';
    protected static string $view = 'filament.admin.clusters.master.pages.import-siswa';
    
    
    protected function getHeaderActions(): array
    {
        return [
            \EightyNine\ExcelImport\ExcelImportAction::make()
                ->color("primary")
                ->use(SiswaImportProcessor::class),
                \Filament\Actions\Action::make('download')
                ->label('Download Template')
                ->color('success')
                ->icon('heroicon-m-document-arrow-down')
                ->url(route('download.template.siswa')) // Route untuk download
                ->openUrlInNewTab(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\Siswa::query())
            ->striped()
            ->defaultPaginationPageOption(10)
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('nm_siswa')
                    ->label('Nama Siswa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('va')
                    ->label('VA')
                    ->searchable(),
                Tables\Columns\TextColumn::make('unit.nm_unit')
                    ->label('Unit')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tgl_lahir')
                    ->label('Tanggal Lahir')
                    ->date(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->color('primary'),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->color('danger')
                    ->icon('heroicon-m-trash'),
            ])
            ->bulkActions([]);
    }
}