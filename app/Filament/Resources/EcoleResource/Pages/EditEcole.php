<?php

namespace App\Filament\Resources\EcoleResource\Pages;

use App\Filament\Resources\EcoleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEcole extends EditRecord
{
    protected static string $resource = EcoleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
