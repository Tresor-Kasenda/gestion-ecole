<?php

namespace App\Filament\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use App\Filament\Resources\ReportResource\Pages;
use App\Models\Classe;
use App\Models\Month;
use App\Models\Option;
use App\Models\Payment;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReportResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationLabel = 'Rapports';

    protected static ?string $slug = 'reports';

    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
        // Will use middleware for protection
        return true;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')
                    ->searchable()
                    ->sortable()
                    ->label('Élève'),
                TextColumn::make('student.option.nom')
                    ->searchable()
                    ->sortable()
                    ->label('Option'),
                TextColumn::make('student.classe.name')
                    ->searchable()
                    ->sortable()
                    ->label('Classe'),
                TextColumn::make('month.name')
                    ->searchable()
                    ->sortable()
                    ->label('Mois'),
                TextColumn::make('type_payment.name')
                    ->searchable()
                    ->sortable()
                    ->label('Type de paiement'),
                TextColumn::make('total_amount')
                    ->prefix('FC ')
                    ->sortable()
                    ->label('Montant total'),
                TextColumn::make('amount')
                    ->prefix('FC ')
                    ->sortable()
                    ->label('Montant payé'),
                TextColumn::make('balance')
                    ->prefix('FC ')
                    ->sortable()
                    ->label('Solde restant'),
                TextColumn::make('payment_date')
                    ->date()
                    ->sortable()
                    ->label('Date de paiement'),
            ])
            ->filters([
                SelectFilter::make('month_id')
                    ->label('Mois')
                    ->options(fn () => Month::pluck('name', 'id')->toArray())
                    ->multiple(),
                SelectFilter::make('option_id')
                    ->label('Option')
                    ->options(fn () => Option::pluck('nom', 'id')->toArray())
                    ->multiple(),
                Filter::make('payment_date')
                    ->form([
                        DatePicker::make('date_from')
                            ->label('Date de début'),
                        DatePicker::make('date_to')
                            ->label('Date de fin'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('payment_date', '>=', $date),
                            )
                            ->when(
                                $data['date_to'],
                                fn (Builder $query, $date): Builder => $query->whereDate('payment_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                // No edit or delete actions for reports
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    FilamentExportBulkAction::make('export')
                        ->fileName('My File') // Default file name
                        ->timeFormat('m y d') // Default time format for naming exports
                        ->defaultFormat('pdf') // xlsx, csv or pdf
                        ->defaultPageOrientation('landscape')
                        ->withHiddenColumns() // Show the columns which are toggled hidden
                        ->fileNameFieldLabel('File Name') // Label for file name input
                        ->formatFieldLabel('Format') // Label for format input
                        ->pageOrientationFieldLabel('Page Orientation') // Label for page orientation input
                        ->filterColumnsFieldLabel('filter columns') // Label for filter columns input
                        ->additionalColumnsFieldLabel('Additional Columns') // Label for additional columns input
                        ->additionalColumnsTitleFieldLabel('Title') // Label for additional columns' title input
                        ->additionalColumnsDefaultValueFieldLabel('Default Value') // Label for additional columns' default value input
                        ->additionalColumnsAddButtonLabel('Add Column'), // Label for additional columns' add button
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ReportDashboard::route('/'),
        ];
    }
}
