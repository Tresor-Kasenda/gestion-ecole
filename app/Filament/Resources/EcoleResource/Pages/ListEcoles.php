<?php

namespace App\Filament\Resources\EcoleResource\Pages;

use App\Filament\Resources\EcoleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEcoles extends ListRecords
{
    protected static string $resource = EcoleResource::class;

    protected function getActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
