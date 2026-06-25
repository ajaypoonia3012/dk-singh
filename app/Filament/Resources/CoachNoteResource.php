<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoachNoteResource\Pages;
use App\Models\CoachNote;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class CoachNoteResource extends Resource
{
    protected static ?string $model = CoachNote::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Members';

    protected static ?string $navigationLabel = 'Coach Notes';

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

                Textarea::make('note')
    ->rows(8)
    ->maxLength(5000)
    ->required()
    ->columnSpanFull(),

                Toggle::make('is_visible')
                    ->label('Visible To Member')
                    ->default(true),

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

                TextColumn::make('note')
                    ->limit(80)
                    ->wrap(),

                IconColumn::make('is_visible')
    ->label('Visible')
    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

            ])
            ->defaultSort(
                'created_at',
                'desc'
            )
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoachNotes::route('/'),
            'create' => Pages\CreateCoachNote::route('/create'),
            'edit' => Pages\EditCoachNote::route('/{record}/edit'),
        ];
    }
}