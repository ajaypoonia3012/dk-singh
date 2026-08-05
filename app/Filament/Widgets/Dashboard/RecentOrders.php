<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\Order;

use Filament\Tables;
use Filament\Tables\Table;

use Filament\Widgets\TableWidget as BaseWidget;

class RecentOrders extends BaseWidget
{
    protected static ?string $heading = 'Recent Orders';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()->latest()
            )
            ->columns([

                Tables\Columns\TextColumn::make('id'),

                Tables\Columns\TextColumn::make('item_type')
                    ->label('Item'),

                Tables\Columns\TextColumn::make('amount')
    ->formatStateUsing(
        fn ($state) => '₹' . number_format((float) $state, 2)
    ),

                Tables\Columns\TextColumn::make('payment_status')
                    ->badge(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),

            ]);
    }
}