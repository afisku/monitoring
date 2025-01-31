<?php

namespace App\Filament\Admin\Clusters\Master\Resources;

use stdClass;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TahunAkademik;
use Filament\Resources\Resource;
use App\Filament\Admin\Clusters\Master;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Clusters\Master\Resources\TahunAkademikResource\Pages;
use App\Filament\Admin\Clusters\Master\Resources\TahunAkademikResource\RelationManagers;

class TahunAkademikResource extends Resource
{
    protected static ?string $model = TahunAkademik::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $cluster = Master::class;

    protected static ?string $navigationLabel = 'Tahun Akademik';

    protected static ?string $modelLabel = 'Tahun Akademik';

    protected static ?string $pluralModelLabel = 'Tahun Akademik';

    protected static ?string $slug = 'tahun-akademik';

    public static function form(Form $form): Form
    {
        return $form
            ->inlineLabel()
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('th_akademik')
                            ->label('Tahun Akademik')
                            ->placeholder('Contoh: 2020/2021')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'required' => 'Tahun akademik tidak boleh kosong',
                                'unique' => 'Tahun akademik sudah ada, gunakan tahun akademik lain',
                            ]),
                        Forms\Components\Toggle::make('status')
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('No')
                    ->width('1%')
                    ->alignCenter()
                    ->state(
                        static function (HasTable $livewire, stdClass $rowLoop): string {
                            return (string) (
                                $rowLoop->iteration +
                                (intval($livewire->getTableRecordsPerPage()) * (
                                    intval($livewire->getTablePage()) - 1
                                ))
                            );
                        }
                    ),
                Tables\Columns\TextColumn::make('th_akademik')
                    ->label('Tahun Akademik')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('status')
                    ->label('Status Aktif')
                    ->alignCenter()
                    ->afterStateUpdated(function ($record, $state) {
                        if ($state) {
                            // Menonaktifkan tahun akademik lain
                            \App\Models\TahunAkademik::where('id', '!=', $record->id)
                                ->update(['status' => false]);
                        }
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                ->iconButton()
                ->color('primary')
                ->icon('heroicon-m-eye'),
                Tables\Actions\EditAction::make()
                ->iconButton()
                ->color('warning')
                ->icon('heroicon-m-pencil-square'),
                Tables\Actions\DeleteAction::make()
                ->iconButton()
                ->color('danger')
                ->icon('heroicon-m-trash')
                ->modalHeading('Hapus Tahun Akademik'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTahunAkademiks::route('/'),
            'create' => Pages\CreateTahunAkademik::route('/create'),
            'edit' => Pages\EditTahunAkademik::route('/{record}/edit'),
        ];
    }
}
