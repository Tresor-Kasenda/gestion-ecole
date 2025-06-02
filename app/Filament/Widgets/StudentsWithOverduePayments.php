<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class StudentsWithOverduePayments extends BaseWidget
{
    protected static ?int $sort = 4;
    protected static ?string $heading = 'Paiements en retard';
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Payment::query()
                    ->where('is_completed', false)
                    ->whereColumn('amount', '<', 'total_amount')
                    ->orWhere(function (Builder $query) {
                        $query->whereNotNull('due_date')
                            ->where('due_date', '<', now());
                    })
                    ->with(['student', 'type_payment'])
            )
            ->columns([
                TextColumn::make('student.name')
                    ->label('Étudiant')
                    ->searchable(),
                TextColumn::make('student.classe.name')
                    ->label('Classe')
                    ->searchable(),
                TextColumn::make('type_payment.name')
                    ->label('Type de paiement')
                    ->searchable(),
                TextColumn::make('due_date')
                    ->label('Date limite')
                    ->date()
                    ->sortable(),
                TextColumn::make('balance')
                    ->label('Montant restant')
                    ->money('CDF')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'partial' => 'warning',
                        'overdue' => 'danger',
                        default => 'secondary',
                    }),
            ])
            ->defaultSort('due_date')
            ->actions([
                \Filament\Tables\Actions\Action::make('send_reminder')
                    ->label('Envoyer rappel')
                    ->icon('heroicon-o-bell')
                    ->action(function (Payment $record) {
                        // TODO: Implémenter l'envoi de rappel
                        // Pour l'instant, on met juste à jour la date de rappel
                        $record->update([
                            'last_reminder_sent' => now()
                        ]);
                    }),
            ]);
    }
}
