<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class PaymentTrendsChart extends ChartWidget
{
    protected static ?string $heading = 'Tendances des paiements';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $payments = Payment::selectRaw('DATE(payment_date) as date, SUM(amount) as total')
            ->where('payment_date', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Montant total des paiements',
                    'data' => $payments->pluck('total')->toArray(),
                    'borderColor' => '#10B981',
                ]
            ],
            'labels' => $payments->pluck('date')->map(function ($date) {
                return Carbon::parse($date)->format('d/m');
            })->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
