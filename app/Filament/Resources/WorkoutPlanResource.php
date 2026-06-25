<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkoutPlanResource\Pages;
use App\Models\WorkoutPlan;

use Filament\Forms;
use Filament\Forms\Form;

use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;

class WorkoutPlanResource extends Resource
{
    protected static ?string $model = WorkoutPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-fire';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('title')
                    ->required(),
Forms\Components\FileUpload::make('thumbnail')
    ->image()
    ->directory('workout-thumbnails'),


                Forms\Components\Textarea::make('description'),

                Forms\Components\RichEditor::make('content')
                    ->required(),

                Forms\Components\TextInput::make('difficulty'),

Forms\Components\Select::make('category')
    ->options([
        'weight_loss' => 'Weight Loss',
        'weight_gain' => 'Weight Gain',
        'muscle_building' => 'Muscle Building',
        'fat_loss' => 'Fat Loss',
        'home_workout' => 'Home Workout',
        'strength' => 'Strength',
        'mobility' => 'Mobility',
        'general_fitness' => 'General Fitness',
    ]),

Forms\Components\Select::make('required_access')
    ->options([
        'public' => 'Public',
        'basic' => 'Basic',
        'pro' => 'Pro',
        'elite' => 'Elite',
    ])
    ->default('public'),

Forms\Components\Select::make('video_type')
    ->options([
        'youtube' => 'YouTube',
        'google_drive' => 'Google Drive',
        'upload' => 'Upload',
    ]),

Forms\Components\TextInput::make('video_url'),

Forms\Components\FileUpload::make('video_file')
    ->directory('workout-videos'),




            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
Tables\Columns\ImageColumn::make('thumbnail'),
                Tables\Columns\TextColumn::make('title'),

                Tables\Columns\TextColumn::make('difficulty'),
Tables\Columns\TextColumn::make('category'),

Tables\Columns\BadgeColumn::make('required_access'),

Tables\Columns\BadgeColumn::make('video_type'),

Tables\Columns\TextColumn::make('category')
    ->badge(),

Tables\Columns\TextColumn::make('required_access')
    ->badge(),


                Tables\Columns\TextColumn::make('created_at')
                    ->date(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkoutPlans::route('/'),
            'create' => Pages\CreateWorkoutPlan::route('/create'),
            'edit' => Pages\EditWorkoutPlan::route('/{record}/edit'),
        ];
    }
}