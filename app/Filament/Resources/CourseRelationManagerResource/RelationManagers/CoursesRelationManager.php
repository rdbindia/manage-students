<?php

namespace App\Filament\Resources\CourseRelationManagerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Livewire\Component;

class CoursesRelationManager extends RelationManager
{
    protected static string $relationship = 'courses';
    protected static ?string $recordTitleAttribute = 'name';
    protected $listeners = ['refreshRelationManager' => '$refresh'];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('advisor_id')
                    ->label('Advisor')
                    ->relationship('advisor', 'name')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Course Name')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->after(function ($record,Component $livewire) {
                    $livewire->dispatch('refreshStudents');
                }),
                Tables\Actions\AttachAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make()->after(function ($record,Component $livewire) {
                    $livewire->dispatch('refreshStudents');
                }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }

    public function refreshTable(): void
    {
        $this->table->refresh();
    }

    protected function saved(array $data): array
    {
        $this->redirect($this->getResource()::getUrl('edit', ['record' => $this->record->id]));
        return $data;
    }
}
