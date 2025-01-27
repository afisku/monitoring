<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use App\Models\Unit;
use Filament\Tables;
use App\Models\Siswa;
use Filament\Forms\Form;
use App\Models\CroscekSd;
use Filament\Tables\Table;
use Filament\Resources\Resource;
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
                        Forms\Components\Select::make('unit_id')
                        ->label('UNIT')
                        ->placeholder('PILIH UNIT')
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
                            ->label('SISWA')
                            ->placeholder('PILIH SISWA')
                            ->disabledOn('edit')
                            ->options(
                                fn(callable $get) => Siswa::query()
                                    ->where('unit_id', $get('unit_id'))
                                    ->pluck('nm_siswa', 'id')
                            )
                            ->searchable()
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
