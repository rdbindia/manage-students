<?php

namespace App\Filament\Resources\AdvisorResource\Pages;

use App\Filament\Resources\AdvisorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdvisor extends EditRecord
{
    protected static string $resource = AdvisorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
