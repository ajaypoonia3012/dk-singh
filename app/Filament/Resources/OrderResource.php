<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;

use Filament\Forms;
use Filament\Forms\Form;

use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
protected static ?string $navigationGroup = 'Commerce';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

               Forms\Components\TextInput::make('user_id')
    ->disabled(),

                Forms\Components\TextInput::make('item_type'),

               Forms\Components\TextInput::make('item_id')
    ->disabled(),

                Forms\Components\TextInput::make('amount')
    ->disabled(),

                Forms\Components\Select::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                    ]),

                Forms\Components\TextInput::make('payment_gateway'),

                Forms\Components\TextInput::make('payment_id'),
Forms\Components\Select::make('order_status')
    ->options([
        'pending' => 'Pending',
        'packed' => 'Packed',
        'shipped' => 'Shipped',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
    ]),

Forms\Components\TextInput::make('courier'),

Forms\Components\TextInput::make('tracking_number'),

Forms\Components\Textarea::make('shipping_address'),

Forms\Components\TextInput::make('city'),

Forms\Components\TextInput::make('state'),

Forms\Components\TextInput::make('pincode'),

            ]);
    }

   public static function table(Table $table): Table
{
    return $table
        ->columns([

Tables\Columns\TextColumn::make('order_number')
    ->searchable()
    ->sortable(),

            Tables\Columns\TextColumn::make('customer_name')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('customer_email')
                ->searchable(),

            Tables\Columns\TextColumn::make('item_type')
                ->badge(),

            Tables\Columns\TextColumn::make('item_id')
                ->label('Item')
                ->formatStateUsing(function ($state, $record) {

                    if ($record->item_type === 'product') {

                        return optional(
                            $record->product
                        )->name ?? 'Deleted Product';

                    }

                    if ($record->item_type === 'plan') {

                        return optional(
                            $record->plan
                        )->name ?? 'Deleted Plan';

                    }

                    return $state;

                }),

            Tables\Columns\TextColumn::make('amount')
                ->money('INR'),

            Tables\Columns\TextColumn::make('payment_status')
                ->badge(),

            Tables\Columns\TextColumn::make('order_status')
                ->badge(),

            Tables\Columns\TextColumn::make('courier'),

            Tables\Columns\TextColumn::make('tracking_number'),

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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}