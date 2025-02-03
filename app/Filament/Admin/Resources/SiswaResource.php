<?php

namespace App\Filament\Admin\Resources;

use stdClass;
use Carbon\Carbon;
use Filament\Forms;
use App\Models\Unit;
use Filament\Tables;
use App\Models\Siswa;
use Filament\Forms\Form;
use Filament\Tables\Table;

use App\Imports\SiswaImport;
use App\Enums\JenisKelaminEnum;
use Filament\Resources\Resource;
use EightyEight\FilamentExcel\Actions\Tables\ImportAction;
use Illuminate\Support\HtmlString;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\SiswaResource\Pages;
use App\Filament\Admin\Resources\SiswaResource\RelationManagers;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Siswa';

    protected static ?string $modelLabel = 'Siswa';

    protected static ?string $pluralModelLabel = 'Siswa';

    // protected static ?string $recordTitleAttribute = 'nm_siswa';

    protected static ?string $slug = 'siswa';

    public static function form(Form $form): Form
    {
        return $form
        ->inlineLabel()
            ->schema([
                    Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('nm_siswa')
                        ->label('Nama Lengkap')
                        ->placeholder('Masukkan Nama Lengkap')
                        ->required()
                        ->maxLength(255)
                        ->extraInputAttributes([
                            'oninput' => 'this.value = this.value.toUpperCase()',
                            ])
                        ->validationMessages([
                            'required' => 'Nama Lengkap tidak boleh kosong',
                            'max' => 'Nama Lengkap tidak boleh lebih dari 255 karakter',
                        ]),
                    Forms\Components\TextInput::make('va')
                        ->label('No. Virtual Account')
                        ->placeholder('Masukkan No. Virtual Account')
                        ->required()
                        ->maxLength(8)
                        ->extraInputAttributes([
                            'oninput' => "this.value = this.value.replace(/[^0-9]/g, '')",
                        ])
                        ->validationMessages([
                            'required' => 'No. Virtual Account tidak boleh kosong',
                            'max' => 'No. Virtual Account tidak boleh lebih dari 8 karakter',
                        ]),
                    Forms\Components\Select::make('jenis_kelamin')
                        ->label('Jenis Kelamin')
                        ->placeholder('Pilih Jenis Kelamin')
                        ->disabledOn('edit')
                        ->native(false)
                        ->options(JenisKelaminEnum::class),
                    Forms\Components\TextInput::make('tempat_lahir')
                        ->label('Tempat Lahir')
                        ->placeholder('Masukkan Tempat Lahir')
                        ->maxLength(50)
                        ->validationMessages([
                            'max' => 'Tempat Lahir tidak boleh lebih dari 50 karakter',
                        ]),
                    Forms\Components\DatePicker::make('tgl_lahir')
                        ->label('Tanggal Lahir')
                        ->placeholder('d/m/Y')
                        ->native(false)
                        ->displayFormat('d/m/Y'),
                        Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->placeholder('Masukkan Email')
                        ->email(),

                        Forms\Components\TextInput::make('telp')
                            ->label('Nomor Telepon')
                            ->placeholder('Masukkan Nomor Telepon')
                            ->maxLength(255),    
                    
                        Forms\Components\TextInput::make('asal_sekolah')
                        ->label('Asal Sekolah')
                        ->placeholder('Masukkan Asal Sekolah')
                        ->maxLength(255)
                        ->extraInputAttributes([
                            'oninput' => 'this.value = this.value.toUpperCase()',
                            ])
                        ->validationMessages([
                            'required' => 'Asal Sekolah tidak boleh kosong',
                            'max' => 'Asal Sekolah tidak boleh lebih dari 255 karakter',
                        ]),

                    Forms\Components\TextInput::make('pindahan')
                        ->label('Pindahan')
                        ->placeholder('Masukkan Pindahan'),

                    Forms\Components\TextInput::make('kab_kota')
                        ->label('Kabupaten/Kota')
                        ->placeholder('Masukkan Kabupaten/Kota')
                        ->maxLength(50)
                        ->validationMessages([
                            'max' => 'Kabupaten/Kota tidak boleh lebih dari 50 karakter',
                        ]),

                        Forms\Components\Select::make('yatim_piatu')
                        ->options([
                            'YA' => 'YA',
                            'TIDAK' => 'TIDAK',
                        ])
                        ->default('TIDAK'),

                    Forms\Components\Select::make('unit_id')
                        ->label('Unit')
                        ->placeholder('PILIH UNIT')
                        ->options(Unit::all()->pluck('nm_unit', 'id'))
                        ->searchable()
                        ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordAction(null)
            ->recordUrl(null)
            ->extremePaginationLinks()
            ->paginated([5, 10, 20, 50])
            ->defaultPaginationPageOption(10)
            ->recordClasses(function () {
            $classes = 'table-vertical-align-top ';
            return $classes;
        })
            ->columns([
                Tables\Columns\TextColumn::make('index')
                ->label('NO')
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
                Tables\Columns\TextColumn::make('nm_siswa')
                    ->label('Nama Lengkap')
                    ->description(function (Siswa $record) {
                        $data = '';
                        if ($record->email) {
                            $data .= '<small>Email : ' . $record->email . '</small>';
                        }
                        if ($record->telp) {
                            $data .= '<br><small>No. Telp : ' . $record->telp . '</small>';
                        }
                        return new HtmlString($data);
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tempat_lahir')
                    ->label('Tempat/Tgl Lahir')
                    ->sortable()
                    ->description(function ($record) {
                        $data = '';
                        if ($record->tgl_lahir) {
                            // Konversi tgl_lahir menjadi objek Carbon
                            $tgl_lahir = Carbon::parse($record->tgl_lahir);
                            // Hitung umur
                            $umur = $tgl_lahir->age;
                            // Format output dengan umur
                            $data = '<small>' . $tgl_lahir->format('d-m-Y') . ' (' . $umur . ' tahun)</small>';
                        }
                        return new HtmlString($data);
                    }),
                Tables\Columns\TextColumn::make('va')
                    ->label('No. VA')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('unit.nm_unit')
                    ->label('Unit')
                    ->badge()
                    ->color(function (string $state): string {
                        return match ($state) {
                            'SMAIT' => 'warning',
                            'SMPIT' => 'success',
                            'SDIT'  => 'danger',
                            'TKIT'  => 'info',
                        };
                    })
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('asal_sekolah')
                    ->label('Asal Sekolah')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('pindahan')
                    ->label('Pindahan')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('yatim_piatu')
                    ->label('Yatim/Piatu')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('kab_kota')
                    ->label('Kabupaten/Kota')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                ->modalHeading('Hapus Siswa'),
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
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
        ];
    }
}
