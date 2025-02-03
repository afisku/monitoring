<?php

namespace App\Filament\Admin\Resources;

use stdClass;
use Carbon\Carbon;
use Filament\Forms;
use App\Models\Unit;
use Filament\Tables;
use App\Models\Siswa;
use App\Models\Divisi;
use Filament\Forms\Form;
use App\Models\CroscekSmp;
use Filament\Tables\Table;
use App\Models\StatusCasis;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\CroscekSmpResource\Pages;
use App\Filament\Admin\Resources\CroscekSmpResource\RelationManagers;

class CroscekSmpResource extends Resource
{
    protected static ?string $model = CroscekSmp::class;

    protected static ?string $navigationGroup = 'Monitoring Casis';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = 'Casis SMPIT';

    protected static ?string $modelLabel = 'Casis SMPIT';

    protected static ?string $pluralModelLabel = 'Casis SMPIT';

    protected static ?string $slug = 'casis-smpit';

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
        ];
    }


    public static function form(Form $form): Form
    {
        return $form
            ->inlineLabel()
            ->schema([
                
                Forms\Components\Section::make()
                    ->columns(1)
                    ->schema([
                    Forms\Components\Select::make('unit_id')
                        ->label('Unit')
                        ->placeholder('Pilih Unit')
                        ->disabled(fn($operation) => $operation == 'edit' || !auth()->user()->hasRole(['superadmin', 'admin']))
                        ->options(Unit::all()->pluck('nm_unit', 'id'))
                        ->default(!auth()->user()->hasRole(['superadmin', 'admin']) && auth()->user()->unit_id != null ? auth()->user()->unit_id : null)
                        ->live()
                        ->searchable()
                        ->required()
                        ->afterStateUpdated(function (callable $set) {
                            $set('siswa_id', null);
                        }),
                    Forms\Components\Select::make('siswa_id')
                            ->label('Siswa')
                            ->placeholder('Pilih Siswa')
                            ->disabledOn('edit')
                            ->options(function (callable $get, ?CroscekSmp $record) {
                                $selectedSiswaIds = CroscekSmp::pluck('siswa_id')->toArray();

                                // Tambahkan siswa yang sedang dipilih jika sedang dalam mode edit
                                if ($record && $record->siswa_id) {
                                    $selectedSiswaIds = array_diff($selectedSiswaIds, [$record->siswa_id]);
                                }

                                // Query untuk mendapatkan daftar siswa
                                $query = Siswa::query()
                                    ->where('unit_id', $get('unit_id'))
                                    ->whereNotIn('id', $selectedSiswaIds);

                                // Tambahkan siswa yang sedang dipilih ke opsi, jika mode edit
                                if ($record && $record->siswa_id) {
                                    $query->orWhere('id', $record->siswa_id);
                                }

                                // Format nama siswa agar tampil: "NAMA - VA"
                                return $query->get()->mapWithKeys(function ($siswa) {
                                return [$siswa->id => "{$siswa->nm_siswa} - {$siswa->va}"];
                                });
                            })
                            ->searchable()
                            ->required(),


                        Forms\Components\Select::make('biodata')
                            ->label('Biodata')
                            ->options([
                                'PERBAIKAN' => 'Perbaikan',
                                'BELUM DIISI' => 'Belum Diisi',
                                'ACC' => 'Acc',
                            ])
                            ->default('ACC')
                            ->nullable(),
                        Forms\Components\Select::make('dokumen')
                            ->label('Dokumen')
                            ->options([
                                'PERBAIKAN' => 'Perbaikan',
                                'BELUM DIISI' => 'Belum Diisi',
                                'ACC' => 'Acc',
                            ])
                            ->default('ACC')
                            ->nullable(),
                            Forms\Components\Select::make('has_request')
                            ->label('Request?')
                            ->options([
                                'YA' => 'Ya',
                                'TIDAK' => 'Tidak',
                            ])
                            ->default('TIDAK')
                            ->reactive(), // Membuat field ini memengaruhi kondisi
                        
                        Forms\Components\Textarea::make('permintaan')
                            ->label('Isi Request')
                            ->rows(3)
                            ->cols(20)
                            ->visible(fn ($get) => $get('has_request') === 'YA') // Tampil jika 'has_request' bernilai "YA"
                            ->nullable(),

                        Forms\Components\Select::make('has_note')
                            ->label('Note?')
                            ->options([
                                'YA' => 'Ya',
                                'TIDAK' => 'Tidak',
                            ])
                            ->default('TIDAK')
                            ->reactive(), // Membuat field ini reaktif untuk memengaruhi visibilitas
                        
                        Forms\Components\Textarea::make('note')
                            ->label('Isi Note')
                            ->rows(3)
                            ->cols(20)
                            ->visible(fn ($get) => $get('has_note') === 'YA') // Tampil jika has_note bernilai "YA"
                            ->nullable(),
                            Forms\Components\Select::make('anak_gtk')
                            ->label('Anak GTK')
                            ->options([
                                'YA' => 'Ya',
                                'TIDAK' => 'Tidak',
                            ])
                            ->default('TIDAK')
                            ->reactive(), // Membuat field ini reaktif untuk memicu kondisi
                        
                            Forms\Components\Select::make('divisi_id')
                            ->label('Divisi')
                            ->options(Divisi::all()->pluck('nm_divisi', 'id'))
                            ->searchable()
                            ->visible(fn ($get) => $get('anak_gtk') === 'YA') // Hanya tampil jika 'anak_gtk' adalah 'YA'
                            ->required(fn ($get) => $get('anak_gtk') === 'YA'), // Wajib diisi jika 'anak_gtk' adalah 'YA'
                        
                        Forms\Components\TextInput::make('nama_GTK')
                            ->label('Nama GTK')
                            ->visible(fn ($get) => $get('anak_gtk') === 'YA') // Hanya tampil jika 'anak_gtk' adalah 'YA'
                            ->required(fn ($get) => $get('anak_gtk') === 'YA'), // Wajib diisi jika 'anak_gtk' adalah 'YA'
                            
                        Forms\Components\Select::make('status_casis_id')
                            ->label('Status')
                            ->options(StatusCasis::all()->pluck('nm_status_casis', 'id'))
                            ->searchable()
                            ->default(fn () => StatusCasis::where('nm_status_casis', 'BELUM TEST')->value('id')) // Ambil ID dari status "BELUM TEST"
                            ->required(),                  
                    ]),  
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
                Tables\Columns\TextColumn::make('siswa.nm_siswa')
                ->label('NAMA SISWA')
                ->description(function ($record) {
                    $data = '';

                    // Tambahkan nomor VA
                    if (!empty($record->siswa?->va)) {
                        $data .= '<small>No. VA: ' . $record->siswa?->va . '</small>';
                    }

                    // Tambahkan tempat lahir
                    if (!empty($record->siswa?->tempat_lahir)) {
                        $data .= ($data ? '<br>' : '') . 
                            '<small>Tempat Lahir: ' . $record->siswa?->tempat_lahir . '</small>';
                    }

                    // Tambahkan tanggal lahir dan umur
                    if ($record->siswa?->tgl_lahir) {
                        $tgl_lahir = Carbon::parse($record->siswa?->tgl_lahir);
                        $umur = $tgl_lahir->age;
                        $data .= ($data ? '<br>' : '') . 
                            '<small>Tanggal Lahir: ' . $tgl_lahir->format('d-m-Y') . 
                            ' (' . $umur . ' tahun)</small>';
                    }

                    return new HtmlString($data);
                })
                ->html()
                ->searchable(),
                Tables\Columns\TextColumn::make('biodata')
                ->label('BIODATA')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('dokumen')
                ->label('DOKUMEN')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('permintaan')
                ->label('REQUEST')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('note')
                ->label('CATATAN')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('anak_gtk')
                    ->label('ANAK GTK')
                    ->description(function ($record) {
                        $data = '';

                        // Perbaiki akses ke divisi
                        if (!empty($record->divisi?->nm_divisi)) {
                            $data .= '<small>Divisi: ' . $record->divisi?->nm_divisi . '</small>';
                        }

                        // Tambahkan nama GTK
                        if (!empty($record->nama_GTK)) {
                            $data .= ($data ? '<br>' : '') . '<small>Nama GTK: ' . $record->nama_GTK . '</small>';
                        }

                        return new HtmlString($data);
                    })
                    ->html()
                    ->searchable(),
                    
                Tables\Columns\SelectColumn::make('status_casis_id')
                ->label('Status')
                ->options(fn () => StatusCasis::pluck('nm_status_casis', 'id')->toArray())
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->selectablePlaceholder(false),
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
                ->modalHeading('Hapus Croscek SMP'),
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
            'index' => Pages\ListCroscekSmps::route('/'),
            'create' => Pages\CreateCroscekSmp::route('/create'),
            'edit' => Pages\EditCroscekSmp::route('/{record}/edit'),
        ];
    }
}
