<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentMembers extends TableWidget
{
    protected static ?string $heading = 'Recent Members';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::latest()->limit(10)
            )
            ->columns([

                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y'),

            ]);
    }
}