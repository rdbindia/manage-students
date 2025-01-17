<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseRelationManagerResource\RelationManagers\CoursesRelationManager;
use App\Filament\Resources\StudentResource\Pages;
use App\Models\Student;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                SpatieMediaLibraryFileUpload::make('profile_photo')
                    ->label('Profile Photo')
                    ->image()
                    ->disk('local')
                    ->directory('profile_photos')
                    ->visibility('public')
                    ->disk('public'),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required(),
                Textarea::make('bio')
                    ->maxLength(1000)
                    ->required(),
                DatePicker::make('date_of_birth')
                    ->required(),
                Select::make('advisor_id')
                    ->relationship('advisor', 'name')
                    ->required(),
                CheckboxList::make('courses')
                    ->relationship('courses', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('profile_photos')
                ->getStateUsing(fn ($record) => $record->getFirstMediaUrl('profile_photos'))
                ->label('Photo')
                ->circular()
                ->size(50),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('advisor.name')->label('Advisor'),
                TextColumn::make('courses.name')->badge()->label('Courses'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CoursesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
