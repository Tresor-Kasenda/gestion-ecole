<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use App\Filament\Widgets\PaymentStatsOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ReportDashboard extends ListRecords
{
    protected static string $resource = ReportResource::class;

    public function getView(): string
    {
        return 'filament.resources.report-resource.pages.report-dashboard';
    }

    protected static ?string $title = 'Tableau de bord des paiements';

    protected function getHeaderWidgets(): array
    {
        return [
            PaymentStatsOverview::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print_report')
                ->label('Imprimer le rapport')
                ->icon('heroicon-o-printer')
                ->action(function () {
                    return redirect()->route('report.generate-pdf');
                })
                ->extraAttributes(['data-action' => 'print']),
        ];
    }
}
