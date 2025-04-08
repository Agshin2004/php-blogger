<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-c-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('username')
                    ->helperText('Username must be unique')
                    ->unique()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('email')->columnSpanFull()->unique(),
                Forms\Components\Select::make('role')
                    ->options([
                        'guest' => 'Guest',
                        'moderator' => 'Moderator',
                        'admin' => 'Admin'
                    ])
                    ->required(),
                Forms\Components\TextInput::make('password')
                    ->password() // Make input type password
                    ->required(
                        fn($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord
                    )
                    ->hidden(
                        fn($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord
                    )
                    ->maxLength(255)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\ImageColumn::make('avatar')
                    ->getStateUsing(fn(User $user) => asset($user->avatar))
                    ->circular(true),
                Tables\Columns\TextColumn::make('username')
            ])
            ->filters([
                //
            ])
            
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
