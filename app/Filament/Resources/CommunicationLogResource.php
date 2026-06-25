<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommunicationLogResource\Pages;
use App\Filament\Resources\CommunicationLogResource\RelationManagers;
use App\Models\CommunicationLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommunicationLogResource extends Resource
{
    protected static ?string $model = CommunicationLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
           ->schema([

    Forms\Components\Select::make('user_id')
        ->relationship('user', 'name')
        ->searchable(),

    Forms\Components\Select::make('communication_provider_id')
        ->relationship('provider', 'name')
        ->searchable(),

    Forms\Components\TextInput::make('channel')
        ->disabled(),

    Forms\Components\TextInput::make('recipient')
        ->disabled(),

    Forms\Components\Textarea::make('message')
        ->rows(8)
        ->disabled(),

    Forms\Components\Select::make('status')
        ->options([
            'pending' => 'Pending',
            'sent' => 'Sent',
            'failed' => 'Failed',
        ])
        ->disabled(),

    Forms\Components\Textarea::make('response')
        ->rows(5)
        ->disabled(),

]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

    Tables\Columns\TextColumn::make('user.name')
        ->label('User')
        ->searchable(),

    Tables\Columns\TextColumn::make('provider.name')
        ->label('Provider'),

    Tables\Columns\TextColumn::make('channel'),

    Tables\Columns\TextColumn::make('recipient'),

    Tables\Columns\BadgeColumn::make('status')
        ->colors([
            'warning' => 'pending',
            'success' => 'sent',
            'danger' => 'failed',
        ]),

    Tables\Columns\TextColumn::make('created_at')
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
            'index' => Pages\ListCommunicationLogs::route('/'),
            'create' => Pages\CreateCommunicationLog::route('/create'),
            'edit' => Pages\EditCommunicationLog::route('/{record}/edit'),
        ];
    }
}
