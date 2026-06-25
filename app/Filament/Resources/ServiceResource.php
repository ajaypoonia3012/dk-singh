<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
protected static ?string $navigationGroup = 'Business';

    public static function form(Form $form): Form
{
    return $form
        ->schema([

    Forms\Components\TextInput::make('title')
        ->required()
        ->maxLength(255),

    Forms\Components\TextInput::make('slug')
        ->required()
        ->maxLength(255),

    Forms\Components\Textarea::make('description')
        ->rows(4)
        ->columnSpanFull(),

    Forms\Components\Repeater::make('features')
        ->schema([
            Forms\Components\TextInput::make('feature')
                ->required(),
        ])
        ->columnSpanFull()
        ->defaultItems(3)
        ->formatStateUsing(function ($state) {

            if (is_string($state)) {
                return collect(json_decode($state, true))
                    ->map(fn ($item) => ['feature' => $item])
                    ->toArray();
            }

            return $state;
        })
        ->dehydrateStateUsing(function ($state) {

            return json_encode(
                collect($state)->pluck('feature')->toArray()
            );

        }),

    Forms\Components\TextInput::make('price')
        ->numeric()
        ->prefix('₹')
        ->required(),

    Forms\Components\TextInput::make('duration')
        ->placeholder('Monthly')
        ->default('Monthly'),

    Forms\Components\TextInput::make('button_text')
        ->default('Enroll'),

    Forms\Components\FileUpload::make('image')
        ->directory('services')
        ->image()
        ->imagePreviewHeight('250')
        ->columnSpanFull(),

    Forms\Components\Toggle::make('featured'),

    Forms\Components\Toggle::make('status')
        ->default(true),

]);
}
public static function table(Table $table): Table
{
    return $table
        ->columns([

            Tables\Columns\ImageColumn::make('image'),

            Tables\Columns\TextColumn::make('title')
                ->searchable(),

            Tables\Columns\TextColumn::make('price'),

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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
