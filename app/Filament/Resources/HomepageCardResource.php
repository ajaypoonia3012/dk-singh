<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomepageCardResource\Pages;
use App\Filament\Resources\HomepageCardResource\RelationManagers;
use App\Models\HomepageCard;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HomepageCardResource extends Resource
{
    protected static ?string $model = HomepageCard::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

   public static function form(Form $form): Form
{
    return $form
        ->schema([

            Forms\Components\TextInput::make('title')
                ->required(),

            Forms\Components\TextInput::make('subtitle'),

            Forms\Components\TextInput::make('icon')
                ->helperText('Example: 🔥 💪 🏆'),

            Forms\Components\TextInput::make('sort_order')
                ->numeric()
                ->default(1),

            Forms\Components\Toggle::make('is_active')
                ->default(true),

        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),

Tables\Columns\TextColumn::make('subtitle')
    ->limit(30),

Tables\Columns\TextColumn::make('icon'),

Tables\Columns\TextColumn::make('sort_order'),

Tables\Columns\IconColumn::make('is_active')
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
            'index' => Pages\ListHomepageCards::route('/'),
            'create' => Pages\CreateHomepageCard::route('/create'),
            'edit' => Pages\EditHomepageCard::route('/{record}/edit'),
        ];
    }
}
