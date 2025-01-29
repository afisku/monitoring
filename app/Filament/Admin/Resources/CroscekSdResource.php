<?php

namespace App\Filament\Admin\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\Unit;
use Filament\Tables;
use App\Models\Siswa;
use Filament\Forms\Form;
use App\Models\CroscekSd;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\CroscekSdResource\Pages;
use App\Filament\Admin\Resources\CroscekSdResource\RelationManagers;

class CroscekSdResource extends Resource
{
    protected static ?string $model = CroscekSd::class;

    protected static ?string $navigationGroup = 'Monitoring Casis';

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = 'Casis SDIT';

    protected static ?string $modelLabel = 'Casis SDIT';

    protected static ?string $pluralModelLabel = 'Casis SDIT';

    protected static ?string $slug = 'casis-sdit';

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
            'validasi',
            'terima',
            'bayar',
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
                        Forms\Components\Hidden::make('unit_id')
    ->default(fn (?CroscekSd $record) => $record?->unit_id ?? auth()->user()->unit_id)
    ->required(),
                    
                        Forms\Components\TextInput::make('nm_unit')
                        ->label('Unit')
                        ->default(fn (?CroscekSd $record) => $record?->unit?->nm_unit ?? auth()->user()->unit?->nm_unit) // Gunakan relasi unit
                        ->disabled() // Hanya untuk ditampilkan
                        ->required(),


                    Forms\Components\Select::make('siswa_id')
                        ->label('Siswa')
                        ->placeholder('Pilih Siswa')
                        ->options(function (callable $get, ?CroscekSd $record) {
                            $unitId = $get('unit_id'); // Ambil unit_id dari state form
                            $selectedSiswaId = $record?->siswa_id; // Ambil siswa_id dari record saat mode edit

                            $query = Siswa::query()
                                ->where('unit_id', $unitId);

                            // Tambahkan siswa yang dipilih dalam mode edit ke opsi
                            if ($selectedSiswaId) {
                                $query->orWhere('id', $selectedSiswaId);
                            }

                            return $query->pluck('nm_siswa', 'id');
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
                        
                        Forms\Components\Select::make('unit_gtk')
                            ->label('Unit GTK')
                            ->options([
                                'TKIT' => 'TKIT',
                                'SDIT' => 'SDIT',
                                'SMPIT' => 'SMPIT',
                                'SMAIT' => 'SMAIT',
                            ])
                            ->visible(fn ($get) => $get('anak_gtk') === 'YA') // Hanya tampil jika 'anak_gtk' adalah 'YA'
                            ->required(fn ($get) => $get('anak_gtk') === 'YA'), // Wajib diisi jika 'anak_gtk' adalah 'YA'
                        
                        Forms\Components\TextInput::make('nama_GTK')
                            ->label('Nama GTK')
                            ->visible(fn ($get) => $get('anak_gtk') === 'YA') // Hanya tampil jika 'anak_gtk' adalah 'YA'
                            ->required(fn ($get) => $get('anak_gtk') === 'YA'), // Wajib diisi jika 'anak_gtk' adalah 'YA'
                        
                    ]),                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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

                    // Tambahkan nomor VA
                    if (!empty($record->unit_gtk)) {
                        $data .= '<small>Unit GTK: ' . $record->unit_gtk . '</small>';
                    }

                    // Tambahkan tempat lahir
                    if (!empty($record->nama_GTK)) {
                        $data .= ($data ? '<br>' : '') . 
                            '<small>Nama GTK: ' . $record->nama_GTK . '</small>';
                    }

                    return new HtmlString($data);
                })
                ->html()
                ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListCroscekSds::route('/'),
            'create' => Pages\CreateCroscekSd::route('/create'),
            'edit' => Pages\EditCroscekSd::route('/{record}/edit'),
        ];
    }
}
