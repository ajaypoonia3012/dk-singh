<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommunicationProviderResource\Pages;
use App\Filament\Resources\CommunicationProviderResource\RelationManagers;
use App\Models\CommunicationProvider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommunicationProviderResource extends Resource
{
    protected static ?string $model = CommunicationProvider::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

    Forms\Components\TextInput::make('name')
        ->required(),

    Forms\Components\Select::make('type')
        ->options([
            'whatsapp' => 'WhatsApp',
            'email' => 'Email',
        ])
        ->required(),

    Forms\Components\TextInput::make('provider')
        ->required(),

    Forms\Components\TextInput::make('api_url'),

    Forms\Components\Textarea::make('api_key'),

    Forms\Components\Textarea::make('api_secret'),

    Forms\Components\TextInput::make('sender_id'),

    Forms\Components\TextInput::make('instance_id'),

    Forms\Components\Toggle::make('is_active')
        ->default(true),

    Forms\Components\Toggle::make('is_default'),

]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

    Tables\Columns\TextColumn::make('name')
        ->searchable(),

    Tables\Columns\TextColumn::make('type'),

    Tables\Columns\TextColumn::make('provider'),

    Tables\Columns\IconColumn::make('is_active')
        ->boolean(),

    Tables\Columns\IconColumn::make('is_default')
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
            'index' => Pages\ListCommunicationProviders::route('/'),
            'create' => Pages\CreateCommunicationProvider::route('/create'),
            'edit' => Pages\EditCommunicationProvider::route('/{record}/edit'),
        ];
    }
}
