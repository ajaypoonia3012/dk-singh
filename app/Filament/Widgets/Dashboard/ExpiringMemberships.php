<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\Membership;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class ExpiringMemberships extends TableWidget
{
    protected static ?string $heading = 'Expiring Memberships';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Membership::with(['user', 'plan'])
                    ->whereNotNull('expires_at')
                    ->whereDate('expires_at', '>=', now())
                    ->whereDate('expires_at', '<=', now()->addDays(7))
                    ->orderBy('expires_at')
            )

            ->columns([

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Member')
                    ->searchable(),

                Tables\Columns\TextColumn::make('plan.name')
                    ->label('Plan'),

                Tables\Columns\TextColumn::make('expires_at')
                    ->date('d M Y'),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Days Left')
                    ->formatStateUsing(fn ($record) => now()->diffInDays($record->expires_at) . ' Days'),

            ])

            ->actions([

                Tables\Actions\Action::make('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => "/admin/memberships/{$record->id}/edit"),

            ]);
    }
}