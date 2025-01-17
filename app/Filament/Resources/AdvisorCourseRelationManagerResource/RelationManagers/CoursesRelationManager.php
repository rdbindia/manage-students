<?php

namespace App\Filament\Resources\AdvisorCourseRelationManagerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CoursesRelationManager extends RelationManager
{
    protected static string $relationship = 'courses';
    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('advisor_id') // Show advisor as a readonly select field
                ->label('Advisor')
                ->options(fn () => [
                    static::getOwnerRecord()?->id => static::getOwnerRecord()?->name,
                ])
                ->default(static::getOwnerRecord()?->id) // Default to the current advisor
                ->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Course Name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('advisor.name')->label('Advisor')->sortable(),
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
