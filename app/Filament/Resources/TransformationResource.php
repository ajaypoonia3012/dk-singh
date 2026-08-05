<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransformationResource\Pages;
use App\Models\Transformation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TransformationResource extends Resource
{
    protected static ?string $model = Transformation::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Client Information')
                    ->schema([

                        Forms\Components\TextInput::make('name')
                            ->required(),

                        Forms\Components\TextInput::make('goal')
                            ->required(),

                        Forms\Components\TextInput::make('program')
                            ->placeholder('Fat Loss Transformation'),

                        Forms\Components\TextInput::make('coach')
                            ->default('DK Singh'),

                    ])
                    ->columns(2),

                Forms\Components\Section::make('Transformation Details')
                    ->schema([

                        Forms\Components\TextInput::make('before_weight')
                            ->numeric()
                            ->suffix('KG'),

                        Forms\Components\TextInput::make('after_weight')
                            ->numeric()
                            ->suffix('KG'),

                        Forms\Components\TextInput::make('weight_loss')
                            ->numeric()
                            ->suffix('KG'),

                        Forms\Components\TextInput::make('duration')
                            ->placeholder('12 Weeks'),

                    ])
                    ->columns(4),

                Forms\Components\Section::make('Transformation Images')
                    ->schema([

                        Forms\Components\Select::make('before_media_id')
                            ->label('Before Photo')
                            ->relationship('beforeMedia', 'name')
                            ->searchable()
                            ->nullable(),

                        Forms\Components\Select::make('after_media_id')
                            ->label('After Photo')
                            ->relationship('afterMedia', 'name')
                            ->searchable()
                            ->nullable(),

                    ])
                    ->columns(2),

                Forms\Components\Section::make('Story')
                    ->schema([

                        Forms\Components\Textarea::make('description')
                            ->rows(3),

                        Forms\Components\RichEditor::make('story')
                            ->columnSpanFull(),

                    ]),

                Forms\Components\Section::make('Publishing')
                    ->schema([

                        Forms\Components\Toggle::make('featured')
                            ->label('Featured Transformation'),

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

                Tables\Columns\ImageColumn::make('beforeMedia.path')
                    ->disk('public')
                    ->label('Before')
                    ->square()
                    ->height(70),

                Tables\Columns\ImageColumn::make('afterMedia.path')
                    ->disk('public')
                    ->label('After')
                    ->square()
                    ->height(70),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('goal')
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('program')
                    ->searchable(),

                Tables\Columns\TextColumn::make('weight_loss')
                    ->suffix(' KG')
                    ->sortable(),

                Tables\Columns\TextColumn::make('duration')
                    ->sortable(),

                Tables\Columns\IconColumn::make('featured')
                    ->label('Featured')
                    ->boolean(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Published')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),

            ])
            ->filters([

                Tables\Filters\TernaryFilter::make('featured'),

                Tables\Filters\TernaryFilter::make('status'),

                Tables\Filters\SelectFilter::make('goal')
                    ->options([
                        'Weight Loss' => 'Weight Loss',
                        'Fat Loss' => 'Fat Loss',
                        'Weight Gain' => 'Weight Gain',
                        'Muscle Building' => 'Muscle Building',
                        'Strength' => 'Strength',
                    ]),

            ])
            ->actions([

                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([

                Tables\Actions\BulkActionGroup::make([

                    Tables\Actions\DeleteBulkAction::make(),

                ]),

            ])
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransformations::route('/'),
            'create' => Pages\CreateTransformation::route('/create'),
            'edit' => Pages\EditTransformation::route('/{record}/edit'),
        ];
    }
}