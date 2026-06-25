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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('name')
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->required(),

                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->directory('transformations')
                    ->required(),

                Forms\Components\TextInput::make('goal')
                    ->required(),

                Forms\Components\TextInput::make('duration')
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\ImageColumn::make('image'),

                Tables\Columns\TextColumn::make('name'),

                Tables\Columns\TextColumn::make('goal'),

                Tables\Columns\TextColumn::make('duration'),

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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransformations::route('/'),
            'create' => Pages\CreateTransformation::route('/create'),
            'edit' => Pages\EditTransformation::route('/{record}/edit'),
        ];
    }
}