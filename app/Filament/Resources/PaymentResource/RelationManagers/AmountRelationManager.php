<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class AmountRelationManager extends RelationManager
{
    protected static string $relationship = 'student';

    protected static ?string $label = 'Historique des paiements';

    public  function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('type_payment.name')
                    ->label('Type de paiement'),
                TextColumn::make('month.name')
                    ->label('Mois'),
                TextColumn::make('total_amount')
                    ->label('Montant total')
                    ->prefix('FC '),
                TextColumn::make('amount')
                    ->label('Montant payé')
                    ->prefix('FC '),
                TextColumn::make('balance')
                    ->label('Solde restant')
                    ->prefix('FC '),
                IconColumn::make('is_completed')
                    ->label('Complété')
                    ->boolean(),
                TextColumn::make('payment_date')
                    ->label('Date de paiement')
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
