<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->successNotificationTitle('Etudiant supprimé avec succès')
                ->color('danger')
                ->requiresConfirmation(),
        ];
    }


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
