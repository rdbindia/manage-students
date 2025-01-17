<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function saved(): void
    {
        $this->emit('refresh-relation-managers');
        logger()->info('Student record created');

        Notification::make()
            ->title('Record Updated')
            ->success()
            ->body('The student record and relationships have been updated.')
            ->send();
    }
}
