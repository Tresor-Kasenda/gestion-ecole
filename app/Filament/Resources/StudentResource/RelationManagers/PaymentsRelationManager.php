<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';
    protected static ?string $title = 'Historique des paiements';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('payment_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('type_payment.name')
                    ->label('Type')
                    ->searchable(),
                TextColumn::make('month.name')
                    ->label('Mois')
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('Montant payé')
                    ->money('CDF')
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->label('Montant total')
                    ->money('CDF'),
                TextColumn::make('balance')
                    ->label('Solde')
                    ->money('CDF')
                    ->color(fn ($record) => $record->balance > 0 ? 'danger' : 'success'),
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'partial' => 'warning',
                        'overdue' => 'danger',
                        default => 'secondary',
                    }),
            ])
            ->actions([
                Action::make('download_receipt')
                    ->label('Télécharger reçu')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn ($record) => route('payment.invoice', $record))
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('payment_date', 'desc');
    }
}
