<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Option;
use App\Models\Payment;
use App\Models\Student;
use App\Models\TypePayment;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $label = 'Paiement';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Section::make('Informations de l\'élève')
                            ->columnSpan(2)
                            ->schema([
                                Select::make('option_id')
                                    ->label("Option de l'eleve")
                                    ->required()
                                    ->options(Option::all()->pluck('nom', 'id'))
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(fn (callable $set) => $set('student_id', null)),

                                Select::make('student_id')
                                    ->label("Nom de l'eleve")
                                    ->required()
                                    ->options(function (callable $get) {
                                        $optionId = $get('option_id');

                                        if (! $optionId) {
                                            return [];
                                        }

                                        return Student::where('option_id', $optionId)
                                            ->pluck('name', 'id');
                                    })
                                    ->searchable()
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set, $state) {
                                        $outstandingPayment = Payment::where('student_id', $state)
                                            ->where('is_completed', false)
                                            ->orderBy('created_at', 'desc')
                                            ->first();

                                        if ($outstandingPayment) {
                                            $set('has_outstanding', true);
                                            $set('outstanding_amount', $outstandingPayment->balance);
                                            $set('outstanding_payment_id', $outstandingPayment->id);
                                        } else {
                                            $set('has_outstanding', false);
                                            $set('outstanding_amount', 0);
                                            $set('outstanding_payment_id', null);
                                        }
                                    }),
                            ]),

                        Section::make('Informations de paiement')
                            ->columnSpan(1)
                            ->schema([
                                Select::make('month_id')
                                    ->label('Motif de paiement')
                                    ->required()
                                    ->relationship('month', 'name')
                                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name}")
                                    ->searchable()
                                    ->disabled(fn (Get $get): bool => $get('has_outstanding') && $get('outstanding_amount') > 0)
                                    ->dehydrated()
                                    ->helperText(function (Get $get) {
                                        if ($get('has_outstanding') && $get('outstanding_amount') > 0) {
                                            return "L'élève doit d'abord régler sa dette de ".number_format($get('outstanding_amount'), 2).' FC avant de payer pour un nouveau mois';
                                        }

                                        return null;
                                    })
                                    ->afterStateHydrated(function (Get $get, callable $set) {
                                        // If student has debt, disable month selection
                                        if ($get('has_outstanding') && $get('outstanding_amount') > 0) {
                                            // Auto-select the month of the outstanding payment
                                            if ($get('outstanding_payment_id')) {
                                                $outstandingPayment = Payment::find($get('outstanding_payment_id'));
                                                if ($outstandingPayment) {
                                                    $set('month_id', $outstandingPayment->month_id);
                                                }
                                            }
                                        }
                                    }),
                                Select::make('type_payment_id')
                                    ->label('Type de paiement')
                                    ->required()
                                    ->relationship('type_payment', 'name')
                                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name}")
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(function (callable $set, $state) {
                                        $paymentType = TypePayment::find($state);
                                        if ($paymentType) {
                                            $set('total_amount', $paymentType->price);
                                        }
                                    }),
                                DatePicker::make('payment_date')
                                    ->maxDate(now())
                                    ->label('Date de paiement')
                                    ->native(false)
                                    ->placeholder('Sélectionner une date')
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                        if ($state && $get('student_id') && $get('month_id') && $get('type_payment_id')) {
                                            $existingPayment = Payment::where('student_id', $get('student_id'))
                                                ->where('month_id', $get('month_id'))
                                                ->where('type_payment_id', $get('type_payment_id'))
                                                ->whereDate('payment_date', $state)
                                                ->first();

                                            if ($existingPayment) {
                                                $set('payment_date', null);
                                                Notification::make()
                                                    ->title('Paiement en double détecté')
                                                    ->body('Un paiement existe déjà pour cet élève avec ces mêmes critères.')
                                                    ->danger()
                                                    ->send();
                                            }
                                        }
                                    }),
                                TextInput::make('total_amount')
                                    ->label('Montant total à payer')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->rules(['regex:/^\d*\.?\d{0,2}$/']) // Permet uniquement 2 décimales
                                    ->placeholder('0.00')
                                    ->helperText('Le montant doit être positif et ne peut avoir que 2 décimales'),

                                TextInput::make('amount')
                                    ->label('Montant payé')
                                    ->numeric()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set, Get $get) {
                                        $totalAmount = floatval($get('total_amount'));
                                        $amountPaid = floatval($get('amount'));
                                        $balance = max(0, $totalAmount - $amountPaid);
                                        $set('balance', $balance);
                                        $set('is_completed', $balance <= 0);
                                    }),

                                TextInput::make('balance')
                                    ->label('Solde restant')
                                    ->numeric()
                                    ->disabled(),

                                TextInput::make('outstanding_payment_id')
                                    ->hidden(),

                                TextInput::make('is_completed')
                                    ->hidden(),
                            ]),

                        Section::make('Dette précédente')
                            ->columnSpan(2)
                            ->hidden(fn (Get $get): bool => ! $get('has_outstanding'))
                            ->schema([
                                Placeholder::make('outstanding_notice')
                                    ->label('Attention: Dette existante')
                                    ->content(fn (Get $get) => "L'élève a une dette de ".number_format($get('outstanding_amount'), 2).' FC')
                                    ->columnSpanFull(),
                                TextInput::make('outstanding_amount')
                                    ->label('Montant dû')
                                    ->disabled(),
                                TextInput::make('has_outstanding')
                                    ->hidden(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')
                    ->searchable()
                    ->label('Eleve'),
                TextColumn::make('student.option.nom')
                    ->searchable()
                    ->label('Option'),
                TextColumn::make('student.classe.name')
                    ->searchable()
                    ->label("Classe de l'eleve"),
                TextColumn::make('type_payment.name')
                    ->searchable()
                    ->label('Type paiement'),
                TextColumn::make('month.name')
                    ->label('Mois de paiement')
                    ->searchable(),
                TextColumn::make('total_amount')
                    ->prefix('FC ')
                    ->label('Montant total')
                    ->searchable(),
                TextColumn::make('amount')
                    ->prefix('FC ')
                    ->label('Montant payé')
                    ->searchable(),
                TextColumn::make('balance')
                    ->prefix('FC ')
                    ->label('Solde restant')
                    ->searchable(),
                IconColumn::make('is_completed')
                    ->boolean()
                    ->label('Complété'),
                TextColumn::make('payment_date')
                    ->label('Date de paiement')
                    ->searchable()
                    ->date(),
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
