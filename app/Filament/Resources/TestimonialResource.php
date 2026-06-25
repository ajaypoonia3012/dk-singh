<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Filament\Resources\TestimonialResource\RelationManagers;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
{
    return $form
        ->schema([

            Forms\Components\TextInput::make('name')
                ->required(),

            Forms\Components\TextInput::make('location'),

            Forms\Components\Textarea::make('review')
                ->required(),

            Forms\Components\FileUpload::make('image'),

            Forms\Components\TextInput::make('rating')
                ->numeric(),

            Forms\Components\Toggle::make('featured'),

            Forms\Components\Toggle::make('status'),

        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([

            Tables\Columns\ImageColumn::make('image'),

            Tables\Columns\TextColumn::make('name'),

            Tables\Columns\TextColumn::make('rating'),

            Tables\Columns\IconColumn::make('featured')
                ->boolean(),

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
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
