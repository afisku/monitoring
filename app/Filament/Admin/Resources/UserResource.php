<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use App\Models\Unit;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Password;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationGroup(): ?string
    {
        return Utils::isResourceNavigationGroupEnabled()
            ? __('filament-shield::filament-shield.nav.group')
            : '';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->placeholder('Enter your name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('username')
                            ->label('Username')
                            ->placeholder('Enter your username')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                            Forms\Components\Select::make('unit_id')
                            ->label('Unit')
                            ->placeholder('PILIH UNIT')
                            ->options(Unit::all()->pluck('nm_unit', 'id'))
                            ->searchable()
                            ->validationMessages([
                                'required' => 'Unit tidak boleh kosong',
                            ]),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->placeholder('Enter your email address')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->placeholder('Enter your password')
                            ->helperText(fn($operation) => $operation == 'edit' ? new HtmlString('<small style="color:red">Isi jika ingin diubah</small>') : '')
                            ->password()
                            ->revealable()
                            ->rules(fn(string $operation) => $operation == 'create' ? ['required', Password::min(8)] : [Password::min(8)])
                            ->confirmed()
                            ->required(fn(string $operation) => $operation == 'create'),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Password confirmation')
                            ->placeholder('Repeat your password')
                            ->password()
                            ->revealable()
                            ->dehydrated(false)
                            ->required(fn(string $operation) => $operation == 'create'),
                        Forms\Components\CheckboxList::make('roles')
                            ->label('Roles')
                            ->required()
                            ->relationship('roles', 'name')
                            ->relationship(
                                name: 'roles',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->where('name', '!=', 'superadmin'),
                            ),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Tidak ada data user')
            ->modifyQueryUsing(fn(Builder $query) => $query->where('username', '!=', 'superadmin'))
            ->recordAction(null)
            ->recordUrl(null)
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Pengguna')
                    ->searchable(),
                Tables\Columns\TextColumn::make('username')
                    ->label('Username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Peran')
                    ->searchable()
                    ->alignCenter()
                    ->badge()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->color('warning')
                    ->icon('heroicon-m-pencil-square'),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->color('danger')
                    ->icon('heroicon-m-trash'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
