<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MessageTemplateResource\Pages;
use App\Filament\Resources\MessageTemplateResource\RelationManagers;
use App\Models\MessageTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MessageTemplateResource extends Resource
{
    protected static ?string $model = MessageTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

    Forms\Components\TextInput::make('name')
        ->required(),

    Forms\Components\TextInput::make('event')
        ->required()
        ->helperText('order_confirmed, shipment_created, shipment_shipped, delivered etc'),

    Forms\Components\Select::make('channel')
        ->options([
            'whatsapp' => 'WhatsApp',
            'email' => 'Email',
        ])
        ->required(),

    Forms\Components\TextInput::make('subject')
        ->maxLength(255),

    Forms\Components\Textarea::make('message')
        ->rows(10)
        ->required(),

    Forms\Components\Toggle::make('is_active')
        ->default(true),

]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

    Tables\Columns\TextColumn::make('name')
        ->searchable(),

    Tables\Columns\TextColumn::make('event')
        ->searchable(),

    Tables\Columns\TextColumn::make('channel'),

    Tables\Columns\IconColumn::make('is_active')
        ->boolean(),

    Tables\Columns\TextColumn::make('updated_at')
        ->dateTime(),

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
            'index' => Pages\ListMessageTemplates::route('/'),
            'create' => Pages\CreateMessageTemplate::route('/create'),
            'edit' => Pages\EditMessageTemplate::route('/{record}/edit'),
        ];
    }
}
