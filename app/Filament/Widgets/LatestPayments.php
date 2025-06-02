<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestPayments extends BaseWidget
{
    protected static ?int $sort = 2;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Payment::query()
                    ->latest('payment_date')
                    ->with(['student', 'type_payment'])
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('student.name')
                    ->label('Étudiant')
                    ->searchable(),
                TextColumn::make('type_payment.name')
                    ->label('Type')
                    ->searchable(),
                TextColumn::make('payment_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Montant')
                    ->money('CDF')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'partial' => 'warning',
                        'overdue' => 'danger',
                        default => 'secondary',
                    })
            ]);
    }
}
