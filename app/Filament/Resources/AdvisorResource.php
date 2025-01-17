<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdvisorCourseRelationManagerResource\RelationManagers\CoursesRelationManager;
use App\Filament\Resources\AdvisorResource\Pages;
use App\Filament\Resources\AdvisorStudentRelationManagerResource\RelationManagers\StudentsRelationManager;
use App\Models\Advisor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AdvisorResource extends Resource
{
    protected static ?string $model = Advisor::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Advisor Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Advisor Name')->sortable()->searchable(),
                TextColumn::make('email')->label('Email')->sortable()->searchable(),
            ])
            ->filters([])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            StudentsRelationManager::class,
            CoursesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdvisors::route('/'),
            'create' => Pages\CreateAdvisor::route('/create'),
            'edit' => Pages\EditAdvisor::route('/{record}/edit'),
        ];
    }
}
