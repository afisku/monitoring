<?php

namespace App\Filament\Admin\Clusters\Master\Resources;

use App\Filament\Admin\Clusters\Master;
use App\Filament\Admin\Clusters\Master\Resources\StatusCasisResource\Pages;
use App\Filament\Admin\Clusters\Master\Resources\StatusCasisResource\RelationManagers;
use App\Models\StatusCasis;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatusCasisResource extends Resource
{
    protected static ?string $model = StatusCasis::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Status Casis';

    protected static ?string $modelLabel = 'Status Casis';

    protected static ?string $pluralModelLabel = 'Status Casis';

    protected static ?string $slug = 'status-casis';

    protected static ?string $cluster = Master::class;

    public static function form(Form $form): Form
    {
        return $form
            ->inlineLabel()
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('status_casis')
                            ->required()
                            ->label('Status Casis')
                            ->maxLength(255),
                        Forms\Components\ColorPicker::make('warna')
                            ->label('Warna'),
                        Forms\Components\TextInput::make('keterangan')
                            ->label('Keterangan')
                            ->maxLength(255),
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
                Tables\Columns\TextColumn::make('status_casis')
                    ->label('Status Casis')
                    ->searchable(),
                Tables\Columns\ColorColumn::make('warna')
                    ->label('Warna'),
                    
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
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
            'index' => Pages\ListStatusCases::route('/'),
            'create' => Pages\CreateStatusCasis::route('/create'),
            'edit' => Pages\EditStatusCasis::route('/{record}/edit'),
        ];
    }
}
