<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Students', \App\Models\Student::count())
                ->description('Total number of registered students')
                ->descriptionIcon('heroicon-o-academic-cap')
                ->color('primary'),

            Stat::make('Total Payments', \App\Models\Payment::count())
                ->description('Number of payments processed')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('Classes', \App\Models\Classe::count())
                ->description('Total number of classes')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('danger'),
        ];
    }
}
