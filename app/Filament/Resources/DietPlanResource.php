<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DietPlanResource\Pages;
use App\Models\DietPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class DietPlanResource extends Resource
{
    protected static ?string $model = DietPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationGroup = 'Commerce';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Diet Plan Information')
                    ->schema([

                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->readOnly(),

                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '4:5',
                            ])
                            ->imageCropAspectRatio('4:5')
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth('800')
                            ->imageResizeTargetHeight('1000')
                            ->disk('public')
                            ->directory('diet-plans')
                            ->imagePreviewHeight('220'),

                        Forms\Components\Textarea::make('description')
                            ->rows(4),

                    ])
                    ->columns(2),

                Forms\Components\Section::make('Diet Details')
                    ->schema([

                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('goal'),

                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->prefix('₹'),

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
                            ])
                            ->searchable(),

                        Forms\Components\Select::make('diet_type')
                            ->options([
                                'veg' => 'Vegetarian',
                                'non_veg' => 'Non Vegetarian',
                            ]),

                        Forms\Components\Select::make('required_access')
                            ->options([
                                'public' => 'Public (Free)',
                                'basic' => 'Basic Plan',
                                'pro' => 'Pro Plan',
                                'elite' => 'Elite Plan',
                            ])
                            ->default('public'),

                    ])
                    ->columns(2),

                Forms\Components\Section::make('Publishing')
                    ->schema([

                        Forms\Components\Toggle::make('featured')
                            ->label('Featured Diet'),

                        Forms\Components\Toggle::make('status')
                            ->label('Published')
                            ->default(true),

                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),

                    ])
                    ->columns(3),

                Forms\Components\Section::make('SEO')
                    ->schema([

                        Forms\Components\TextInput::make('seo_title'),

                        Forms\Components\Textarea::make('seo_description')
                            ->rows(4),

                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\ImageColumn::make('image')
                    ->disk('public')
                    ->square()
                    ->height(60),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('goal')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('category'),

                Tables\Columns\BadgeColumn::make('diet_type'),

                Tables\Columns\BadgeColumn::make('required_access'),

                Tables\Columns\TextColumn::make('price')
                    ->money('INR'),

                Tables\Columns\IconColumn::make('featured')
                    ->boolean(),

                Tables\Columns\IconColumn::make('status')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),

            ])
            ->filters([

                Tables\Filters\SelectFilter::make('category')
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

                Tables\Filters\SelectFilter::make('diet_type')
                    ->options([
                        'veg' => 'Vegetarian',
                        'non_veg' => 'Non Vegetarian',
                    ]),

                Tables\Filters\TernaryFilter::make('featured'),

                Tables\Filters\TernaryFilter::make('status'),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }

    public static function getRelations(): array
    {
        return [];
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