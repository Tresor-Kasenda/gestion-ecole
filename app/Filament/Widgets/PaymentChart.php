<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use App\Models\Student;
use Filament\Widgets\BarChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class PaymentChart extends BarChartWidget
{
    protected static ?string $heading = 'Statistiques des paiements par jour';

    protected static ?string $maxHeight = null;

    protected  int | string | array $columnSpan = 'full';

    protected static ?string $pollingInterval = '30s';

    protected function getFilters(): ?array
    {
        return [
            'today' => "Aujourd'hui",
            'week' => 'Cette semaine',
            'month' => 'Ce mois',
            'year' => 'Cette année',
        ];
    }

    protected static ?array $options = [
        'maintainAspectRatio' => false,
        'responsive' => true,
        'scales' => [
            'y' => [
                'beginAtZero' => true,
                'ticks' => [
                    'precision' => 0,
                ],
            ],
        ],
        'plugins' => [
            'legend' => [
                'display' => true,
                'position' => 'top',
            ],
            'tooltip' => [
                'mode' => 'index',
                'intersect' => false,
            ],
        ],
    ];

    protected function getData(): array
    {
        $activeFilter = $this->filter ?? 'week';
        $studentCount = Student::count();

        $query = Trend::model(Payment::class);

        switch ($activeFilter) {
            case 'today':
                $data = $query->between(
                    start: now()->startOfDay(),
                    end: now()->endOfDay(),
                )->perHour()->count();
                break;
            case 'week':
                $data = $query->between(
                    start: now()->startOfWeek(),
                    end: now()->endOfWeek(),
                )->perDay()->count();
                break;
            case 'month':
                $data = $query->between(
                    start: now()->startOfMonth(),
                    end: now()->endOfMonth(),
                )->perDay()->count();
                break;
            case 'year':
            default:
                $data = $query->between(
                    start: now()->startOfYear(),
                    end: now()->endOfYear(),
                )->perMonth()->count();
                break;
        }

        $totalAmounts = [];
        foreach ($data as $point) {
            $date = $point->date;
            $totalAmount = Payment::when($activeFilter === 'today', function ($query) use ($date) {
                return $query->whereDate('created_at', $date)->whereTime('created_at', '>=', $date . ':00')->whereTime('created_at', '<', $date . ':59');
            })->when($activeFilter === 'week' || $activeFilter === 'month', function ($query) use ($date) {
                return $query->whereDate('created_at', $date);
            })->when($activeFilter === 'year', function ($query) use ($date) {
                return $query->whereYear('created_at', substr($date, 0, 4))->whereMonth('created_at', substr($date, 5, 2));
            })->sum('amount');

            $totalAmounts[] = $totalAmount;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Nombre de paiements',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#36A2EB',
                ],
                [
                    'label' => 'Montant total (USD)',
                    'data' => $totalAmounts,
                    'backgroundColor' => '#FF6384',
                    'type' => 'line',
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }
}
