<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Commerce';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Product Information')
                    ->schema([

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                fn (Set $set, ?string $state) => $set('slug', Str::slug($state))
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->readOnly(),

                        Forms\Components\TextInput::make('sku')
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->rows(5),

                    ])
                    ->columns(2),

                Forms\Components\Section::make('Product Details')
                    ->schema([

                        Forms\Components\Select::make('media_id')
                            ->label('Product Image')
                            ->relationship('media', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Select an image from the Media Library.'),

                        Forms\Components\Select::make('category')
                            ->options([
                                'Weight Loss' => 'Weight Loss',
                                'Weight Gain' => 'Weight Gain',
                                'Hair Care' => 'Hair Care',
                                'Men\'s Health' => 'Men\'s Health',
                                'Protein' => 'Protein',
                                'Vitamins' => 'Vitamins',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->prefix('₹')
                            ->required(),

                        Forms\Components\TextInput::make('weight')
                            ->numeric()
                            ->suffix('KG')
                            ->required(),

                        Forms\Components\Toggle::make('featured')
                            ->label('Show on Homepage'),

                        Forms\Components\Toggle::make('status')
                            ->label('Published'),

                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),

                    ])
                    ->columns(2),

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

                Tables\Columns\ImageColumn::make('media.path')
                    ->disk('public')
                    ->square()
                    ->height(60),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sku')
                    ->searchable(),

                Tables\Columns\TextColumn::make('price')
                    ->money('INR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('weight')
                    ->suffix(' KG'),

                Tables\Columns\IconColumn::make('featured')
                    ->label('Homepage')
                    ->boolean(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Published')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),

            ])

            ->filters([

                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'Weight Loss' => 'Weight Loss',
                        'Weight Gain' => 'Weight Gain',
                        'Hair Care' => 'Hair Care',
                        'Men\'s Health' => 'Men\'s Health',
                        'Protein' => 'Protein',
                        'Vitamins' => 'Vitamins',
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}