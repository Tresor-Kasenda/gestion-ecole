<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EcoleResource\Pages;
use App\Models\Ecole;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;

class EcoleResource extends Resource
{
    protected static ?string $model = Ecole::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?string $navigationLabel = 'Ecole';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label("Nom de l'ecole")
                    ->autofocus()
                    ->required(),
                TextInput::make('adresse')
                    ->label("Adresse de l'ecole")
                    ->autofocus()
                    ->required(),
                TextInput::make('email')
                    ->label("Email de l'ecole")
                    ->autofocus()
                    ->required(),
                TextInput::make('telephone')
                    ->label("Telephone de l'ecole")
                    ->autofocus()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label("Nom de l'ecole")
                    ->searchable()
                    ->sortable(),
                TextColumn::make('adresse')
                    ->label("Adresse de l'ecole")
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label("Email de l'ecole")
                    ->searchable()
                    ->sortable(),
                TextColumn::make('telephone')
                    ->label("Telephone de l'ecole")
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListEcoles::route('/'),
            'create' => Pages\CreateEcole::route('/create'),
            'edit' => Pages\EditEcole::route('/{record}/edit'),
        ];
    }
}
