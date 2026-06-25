<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanResource\Pages;
use App\Models\Plan;

use Filament\Forms;
use Filament\Forms\Form;

use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;

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
                            ->required(),

Forms\Components\TextInput::make('slug')
    ->unique(ignoreRecord: true)
    ->helperText('basic-plan, pro-plan, elite-plan'),

                        Forms\Components\Textarea::make('description')
                            ->rows(4),

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
                            ->directory('plans'),

                        Forms\Components\Repeater::make('features')
                            ->schema([

                                Forms\Components\TextInput::make('feature')
                                    ->required(),

                            ])
                            ->columns(1)
                            ->defaultItems(3)
                            ->collapsible(),

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

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
    ->formatStateUsing(
        fn ($state) => '₹' . number_format((float) $state, 2)
    ),

                Tables\Columns\TextColumn::make('discount_price')
    ->formatStateUsing(
        fn ($state) => $state
            ? '₹' . number_format((float) $state, 2)
            : '-'
    ),

                Tables\Columns\BadgeColumn::make('access_type')
                    ->colors([
                        'primary' => 'basic',
                        'warning' => 'pro',
                        'success' => 'elite',
                    ]),

                Tables\Columns\IconColumn::make('featured')
                    ->boolean(),

                Tables\Columns\IconColumn::make('status')
                    ->boolean(),

            ])
            ->actions([

                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make(),

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