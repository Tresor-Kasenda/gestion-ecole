<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\DB;

class PaymentStatsOverview extends StatsOverviewWidget
{
    protected function getCards(): array
    {
        $totalPayments = Payment::sum('amount');
        $outstandingBalance = Payment::sum('balance');
        $completedPayments = Payment::where('is_completed', true)->count();
        $pendingPayments = Payment::where('is_completed', false)->count();

        $monthlyPayments = Payment::select(DB::raw('SUM(amount) as total'))
            ->whereMonth('payment_date', now()->month)
            ->first()->total ?? 0;

        return [
            Card::make('Total des paiements', 'FC '.number_format($totalPayments, 2))
                ->description('Tous les paiements reçus')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),
            Card::make('Paiements du mois', 'FC '.number_format($monthlyPayments, 2))
                ->description('Pour le mois en cours')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('primary'),
            Card::make('Solde restant', 'FC '.number_format($outstandingBalance, 2))
                ->description('Total des soldes impayés')
                ->descriptionIcon('heroicon-o-exclamation-circle')
                ->color('warning'),
            Card::make('Statut des paiements', $completedPayments.' complétés / '.$pendingPayments.' en attente')
                ->description('Ratio de complétion')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('info'),
        ];
    }
}
