<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Classe;
use App\Models\Option;
use App\Models\Student;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $label = "Eleves";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations sur l\'etudiant')
                    ->columns(2)
                    ->description('Remplissez les informations de l\'etudiant')
                    ->schema([
                        Select::make('option_id')
                            ->label('Section')
                            ->options(Option::all()->pluck('nom', 'id'))
                            ->searchable(),
                        Select::make('classe_id')
                            ->label('Classe')
                            ->options(Classe::all()->pluck('name', 'id'))
                            ->searchable(),
                        TextInput::make('name')
                            ->label("Nom de l'etudiant")
                            ->autofocus()
                            ->required(),
                        TextInput::make('lastname')
                            ->label("Postnom de l'etudiant")
                            ->autofocus()
                            ->required(),
                        TextInput::make('firstname')
                            ->label("Prenom de l'etudiant")
                            ->autofocus()
                            ->required(),
                        TextInput::make('address')
                            ->label("Adresse de l'etudiant")
                            ->autofocus()
                            ->required(),
                        DatePicker::make('birthdays')
                            ->label("Date de naissance de l'etudiant")
                            ->autofocus()
                            ->native(false)
                            ->placeholder('jj/mm/aaaa')
                            ->required(),
                        TextInput::make('matricule')
                            ->label('Matricule')
                            ->default(function () {
                                // Generate a unique ID with format: YEAR-OPTION-SEQUENCE
                                $year = date('Y');
                                $sequence = (Student::max('id') ?? 0) + 1;
                                return strtoupper($year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT));
                            })
                            ->disabled()
                            ->dehydrated() // Ensures the value is saved to the database
                            ->required(),
                        Select::make('gender')
                            ->label("Sexe de l'etudiant")
                            ->options([
                                'male' => 'Homme',
                                'female' => 'Femme',
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('option.nom')
                    ->label('Option')
                    ->searchable(),
                TextColumn::make('classe.name')
                    ->label('Classe')
                    ->searchable(),
                TextColumn::make('name')
                    ->label("Nom complet")
                    ->formatStateUsing(fn (Student $record): string =>
                    "{$record->name} {$record->lastname} {$record->firstname}")
                    ->wrap()
                    ->searchable(['name', 'lastname', 'firstname']),
                TextColumn::make('gender')
                    ->label("Sexe")
                    ->searchable(),
                TextColumn::make('birthdays')
                    ->date()
                    ->label("Date de naissance")
                    ->searchable(),
                TextColumn::make('matricule')
                    ->label("Matricule")
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('Option')
                    ->relationship('option', 'nom', fn (Builder $query) => $query),
                Tables\Filters\SelectFilter::make('Classe')
                    ->relationship('classe', 'name', fn (Builder $query) => $query),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
