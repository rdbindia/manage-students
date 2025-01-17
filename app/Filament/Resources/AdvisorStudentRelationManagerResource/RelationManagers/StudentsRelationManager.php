<?php

namespace App\Filament\Resources\AdvisorStudentRelationManagerResource\RelationManagers;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class StudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'students';
    protected static ?string $recordTitleAttribute = 'name';
    protected $listeners = ['refreshRelationManager' => '$refresh'];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required(),
                Textarea::make('bio')
                    ->maxLength(1000),
                DatePicker::make('date_of_birth')
                    ->required(),
                Select::make('advisor_id') // Show advisor as a readonly select field
                ->label('Advisor')
                    ->options(fn () => [
                        static::getOwnerRecord()?->id => static::getOwnerRecord()?->name,
                    ])
                    ->default(static::getOwnerRecord()?->id) // Default to the current advisor
                    ->disabled(),
                CheckboxList::make('courses')
                    ->relationship('courses', 'name')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('date_of_birth')->date(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AssociateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DissociateAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DissociateBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
