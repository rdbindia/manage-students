<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Livewire\Attributes\On;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    #[On('refreshStudents')]
    public function refreshStudents(): void
    {
        $this->fillForm();
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->redirect($this->getResource()::getUrl('edit', ['record' => $this->record->id]));
        return $data;
    }
}
