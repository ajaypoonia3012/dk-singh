<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourierProviderResource\Pages;
use App\Models\CourierProvider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CourierProviderResource extends Resource
{
    protected static ?string $model = CourierProvider::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Commerce';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('name')
                    ->required(),

                Forms\Components\Select::make('provider_type')
                    ->options([

                        'delhivery' => 'Delhivery',
                        'shiprocket' => 'Shiprocket',
                        'bluedart' => 'Blue Dart',
                        'dtdc' => 'DTDC',
                        'amazon_shipping' => 'Amazon Shipping',

                    ])
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('api_url'),

                Forms\Components\TextInput::make('api_key')
                    ->password()
                    ->revealable()
                    ->autocomplete('new-password')
                    ->afterStateHydrated(fn (Forms\Components\TextInput $component) => $component->state(null))
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->helperText('Leave blank to keep the stored key.'),

                Forms\Components\TextInput::make('api_secret')
                    ->password()
                    ->revealable()
                    ->autocomplete('new-password')
                    ->afterStateHydrated(fn (Forms\Components\TextInput $component) => $component->state(null))
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->helperText('Leave blank to keep the stored secret.'),

                Forms\Components\Toggle::make('is_active')
                    ->default(true),

                Forms\Components\Toggle::make('is_default')
                    ->default(false),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('provider_type'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_default')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y'),

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
            'index' => Pages\ListCourierProviders::route('/'),
            'create' => Pages\CreateCourierProvider::route('/create'),
            'edit' => Pages\EditCourierProvider::route('/{record}/edit'),
        ];
    }
}
