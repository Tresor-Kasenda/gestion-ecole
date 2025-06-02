<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PaymentStats extends BaseWidget
{
    protected function getStats(): array
    {
        $today = now();
        $startOfMonth = $today->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();

        $totalPaymentsThisMonth = Payment::whereBetween('payment_date', [$startOfMonth, $endOfMonth])->sum('amount');
        $overduePayments = Payment::overdue()->count();
        $partialPayments = Payment::partiallyPaid()->count();

        return [
            Stat::make('Paiements ce mois', number_format($totalPaymentsThisMonth, 2) . ' FC')
                ->description('Total des paiements du mois en cours')
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make('Paiements en retard', $overduePayments)
                ->description('Nombre de paiements en retard')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'),

            Stat::make('Paiements partiels', $partialPayments)
                ->description('Nombre de paiements partiels')
                ->descriptionIcon('heroicon-m-scale')
                ->color('warning'),
        ];
    }
}
