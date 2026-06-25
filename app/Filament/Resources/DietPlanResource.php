<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DietPlanResource\Pages;
use App\Models\DietPlan;

use Filament\Forms;
use Filament\Forms\Form;

use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;

class DietPlanResource extends Resource
{
    protected static ?string $model = DietPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';
protected static ?string $navigationGroup = 'Commerce';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('title')
                    ->required(),

                Forms\Components\Textarea::make('description'),

                Forms\Components\RichEditor::make('content')
                    ->required(),

                Forms\Components\TextInput::make('goal'),

Forms\Components\Select::make('category')
    ->options([
        'weight_loss' => 'Weight Loss',
        'weight_gain' => 'Weight Gain',
        'muscle_building' => 'Muscle Building',
        'fat_loss' => 'Fat Loss',
        'pcos' => 'PCOS',
        'diabetic' => 'Diabetic',
        'high_protein' => 'High Protein',
        'general_wellness' => 'General Wellness',
    ]),

Forms\Components\Select::make('diet_type')
    ->options([
        'veg' => 'Vegetarian',
        'non_veg' => 'Non Vegetarian',
    ]),

Forms\Components\Select::make('required_access')
    ->options([
        'public' => 'Public',
        'basic' => 'Basic',
        'pro' => 'Pro',
        'elite' => 'Elite',
    ])
    ->default('public'),


                Forms\Components\Toggle::make('status'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([



                Tables\Columns\TextColumn::make('title'),

                Tables\Columns\TextColumn::make('goal'),


Tables\Columns\TextColumn::make('category'),

Tables\Columns\BadgeColumn::make('diet_type'),

Tables\Columns\BadgeColumn::make('required_access'),

                Tables\Columns\IconColumn::make('status')
                    ->boolean(),

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
            'index' => Pages\ListDietPlans::route('/'),
            'create' => Pages\CreateDietPlan::route('/create'),
            'edit' => Pages\EditDietPlan::route('/{record}/edit'),
        ];
    }
}