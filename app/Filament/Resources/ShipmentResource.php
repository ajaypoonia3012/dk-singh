<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShipmentResource\Pages;
use App\Models\Shipment;

use Filament\Forms;
use Filament\Forms\Form;

use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;

class ShipmentResource extends Resource
{
    protected static ?string $model = Shipment::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Commerce';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('order_id')
                    ->relationship('order', 'order_number')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('courier_provider_id')
                    ->relationship('courierProvider', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('awb_number'),

                Forms\Components\TextInput::make('tracking_number'),

                Forms\Components\Select::make('shipment_status')
                    ->options([
                        'created' => 'Created',
                        'packed' => 'Packed',
                        'shipped' => 'Shipped',
                        'out_for_delivery' => 'Out For Delivery',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),

                Forms\Components\Select::make('pickup_status')
                    ->options([
                        'pending' => 'Pending',
                        'scheduled' => 'Scheduled',
                        'picked_up' => 'Picked Up',
                    ]),

                Forms\Components\TextInput::make('tracking_url'),

                Forms\Components\TextInput::make('label_url'),

                Forms\Components\DatePicker::make('estimated_delivery'),

                Forms\Components\DateTimePicker::make('picked_up_at'),

                Forms\Components\DateTimePicker::make('delivered_at'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('Order'),

                Tables\Columns\TextColumn::make('courierProvider.name')
                    ->label('Courier'),

                Tables\Columns\TextColumn::make('awb_number'),

                Tables\Columns\TextColumn::make('tracking_number'),

                Tables\Columns\BadgeColumn::make('shipment_status'),

                Tables\Columns\BadgeColumn::make('pickup_status'),

                Tables\Columns\TextColumn::make('estimated_delivery')
                    ->date(),

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
            'index' => Pages\ListShipments::route('/'),
            'create' => Pages\CreateShipment::route('/create'),
            'edit' => Pages\EditShipment::route('/{record}/edit'),
        ];
    }
}