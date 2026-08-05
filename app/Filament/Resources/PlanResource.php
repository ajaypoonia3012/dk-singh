<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanResource\Pages;
use App\Models\Plan;

use Filament\Forms;
use Filament\Forms\Form;

use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Set;
use Illuminate\Support\Str;


class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Commerce';

    protected static ?string $navigationLabel = 'Plans';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Plan Information')
                    ->schema([

                        Forms\Components\TextInput::make('name')
    ->required()
    ->live(onBlur: true)
    ->afterStateUpdated(function (Set $set, ?string $state) {
        $set('slug', Str::slug($state));
    }),

Forms\Components\TextInput::make('slug')
    ->required()
    ->unique(ignoreRecord: true)
    ->readOnly()
    ->helperText('Automatically generated'),


                        Forms\Components\Textarea::make('description')
                            ->rows(4)
->required(),

                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->prefix('₹')
                            ->required(),

                        Forms\Components\TextInput::make('discount_price')
                            ->numeric()
                            ->prefix('₹'),

                        Forms\Components\TextInput::make('duration')
                            ->placeholder('1 Month'),

                        Forms\Components\Select::make('billing_cycle')
                            ->options([
                                'month' => 'Month',
                                'year' => 'Year',
                                'lifetime' => 'Lifetime',
                            ])
                            ->default('month'),

                        Forms\Components\TextInput::make('badge')
                            ->placeholder('Most Popular'),

                        Forms\Components\TextInput::make('button_text')
                            ->default('Join Now'),

                        Forms\Components\TextInput::make('button_link')
                            ->placeholder('/checkout/basic-plan'),

                        Forms\Components\Select::make('access_type')
                            ->options([
                                'basic' => 'Basic',
                                'pro' => 'Pro',
                                'elite' => 'Elite',
                            ])
                            ->default('basic'),

                        Forms\Components\FileUpload::make('thumbnail')
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
    ->directory('plans')
->panelAspectRatio('4:5')
    ->imagePreviewHeight('220'),

                        Forms\Components\Repeater::make('features')
                            ->schema([

                                Forms\Components\TextInput::make('feature')
                                    ->required(),

                            ])
                            ->columns(1)
                            ->defaultItems(5)
->collapsible()
->reorderable()
->cloneable(),

                        Forms\Components\Toggle::make('featured')
                            ->default(false),

                        Forms\Components\Toggle::make('status')
                            ->default(true),

                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),

                    ])
                    ->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

Tables\Columns\ImageColumn::make('thumbnail')
    ->disk('public')
    ->square()
    ->height(60),


                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
    ->money('INR')
    ->sortable(),

                Tables\Columns\TextColumn::make('discount_price')
    ->money('INR')
    ->sortable(),

                Tables\Columns\BadgeColumn::make('access_type')
    ->colors([
        'primary' => 'basic',
        'warning' => 'pro',
        'success' => 'elite',
    ])
    ->sortable(),

                Tables\Columns\IconColumn::make('featured')
                    ->boolean(),

                Tables\Columns\IconColumn::make('status')
                    ->boolean(),

            ])
            ->actions([

                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make(),

            ])

->filters([

    Tables\Filters\TernaryFilter::make('featured'),

    Tables\Filters\TernaryFilter::make('status'),

    Tables\Filters\SelectFilter::make('access_type')
        ->options([
            'basic' => 'Basic',
            'pro' => 'Pro',
            'elite' => 'Elite',
        ]),

])


            ->bulkActions([

                Tables\Actions\DeleteBulkAction::make(),

            ])
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [

            'index' => Pages\ListPlans::route('/'),

            'create' => Pages\CreatePlan::route('/create'),

            'edit' => Pages\EditPlan::route('/{record}/edit'),

        ];
    }
}