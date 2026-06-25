<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationResource\Pages;
use App\Filament\Resources\NotificationResource\RelationManagers;
use App\Models\Notification;
use Filament\Forms;
use Filament\Forms\Form;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NotificationResource extends Resource
{
    protected static ?string $model = Notification::class;

    protected static ?string $navigationIcon =
    'heroicon-o-bell';

protected static ?string $navigationLabel =
    'Notifications';

protected static ?string $navigationGroup =
    'Members';

    public static function form(Form $form): Form
{
    return $form
        ->schema([

            Select::make('user_id')
                ->label('Member')
                ->options(
                    User::orderBy('name')
                        ->pluck('name', 'id')
                )
                ->searchable()
                ->required(),

            TextInput::make('title')
                ->required()
                ->maxLength(255),

            Textarea::make('message')
                ->rows(5)
                ->required()
                ->columnSpanFull(),

            Toggle::make('is_read')
                ->default(false),

        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

    TextColumn::make('user.name')
        ->label('Member')
        ->searchable()
        ->sortable(),

    TextColumn::make('title')
        ->searchable()
        ->limit(40),

    IconColumn::make('is_read')
        ->boolean(),

    TextColumn::make('created_at')
        ->dateTime('d M Y H:i')
        ->sortable(),

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
            'index' => Pages\ListNotifications::route('/'),
            'create' => Pages\CreateNotification::route('/create'),
            'edit' => Pages\EditNotification::route('/{record}/edit'),
        ];
    }
}
