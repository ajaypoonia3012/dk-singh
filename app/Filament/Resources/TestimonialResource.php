<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Client Information')
                    ->schema([

                        Forms\Components\TextInput::make('name')
                            ->required(),

                        Forms\Components\TextInput::make('location'),

                        Forms\Components\TextInput::make('profession'),

                        Forms\Components\TextInput::make('program')
                            ->label('Fitness Program'),

                        Forms\Components\TextInput::make('transformation')
                            ->label('Transformation Result')
                            ->placeholder('Lost 18 KG'),

                    ])
                    ->columns(2),

                Forms\Components\Section::make('Review')
                    ->schema([

                        Forms\Components\Textarea::make('review')
                            ->rows(6)
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Select::make('rating')
                            ->options([
                                5 => '★★★★★',
                                4 => '★★★★',
                                3 => '★★★',
                                2 => '★★',
                                1 => '★',
                            ])
                            ->default(5)
                            ->required(),

                    ]),

                Forms\Components\Section::make('Client Photo')
                    ->schema([

                        Forms\Components\Select::make('media_id')
    ->label('Client Photo')
    ->relationship('media', 'name')
    ->searchable()
    ->preload()
    ->required(),
                    ]),

                Forms\Components\Section::make('Publishing')
                    ->schema([

                        Forms\Components\Toggle::make('featured')
                            ->label('Featured Testimonial'),

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

                Tables\Columns\ImageColumn::make('media.path')
    ->disk('public')
                    ->square()
                    ->height(60),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('program')
                    ->limit(25),

                Tables\Columns\TextColumn::make('transformation')
                    ->label('Result'),

                Tables\Columns\TextColumn::make('rating')
                    ->badge(),

                Tables\Columns\IconColumn::make('featured')
                    ->boolean(),

                Tables\Columns\IconColumn::make('status')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),

            ])

            ->filters([

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

            'index' => Pages\ListTestimonials::route('/'),

            'create' => Pages\CreateTestimonial::route('/create'),

            'edit' => Pages\EditTestimonial::route('/{record}/edit'),

        ];
    }
}