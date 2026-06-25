<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;

use App\Models\User;

use Filament\Forms;
use Filament\Forms\Form;

use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'CRM';

    protected static ?string $navigationLabel = 'Users';

    /*
    |--------------------------------------------------------------------------
    | FORM
    |--------------------------------------------------------------------------
    */

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('name')
                    ->required(),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),

                Forms\Components\TextInput::make('phone'),

                Forms\Components\TextInput::make('whatsapp_number'),

                Forms\Components\Select::make('gender')
                    ->options([

                        'Male' => 'Male',
                        'Female' => 'Female',

                    ]),

                Forms\Components\TextInput::make('age')
                    ->numeric(),

                Forms\Components\TextInput::make('height')
                    ->numeric(),

                Forms\Components\TextInput::make('weight')
                    ->numeric(),

                Forms\Components\TextInput::make('bmi')
                    ->numeric(),

                Forms\Components\TextInput::make('goal'),

                Forms\Components\Textarea::make('medical_conditions'),

                Forms\Components\Toggle::make('is_coach'),

            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    public static function table(Table $table): Table
    {
        return $table

            ->columns([

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone'),

                Tables\Columns\TextColumn::make('goal')
                    ->badge(),

                Tables\Columns\TextColumn::make('bmi')
                    ->label('BMI')
                    ->sortable(),

                Tables\Columns\TextColumn::make('activeMembership.plan.name')
                    ->label('Plan')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('activeMembership.status')

                    ->label('Membership')

                    ->formatStateUsing(function ($state, $record) {

                        if (!$record->activeMembership) {

                            return 'No Membership';

                        }

                        if (
                            $record->activeMembership->expires_at &&
                            $record->activeMembership->expires_at < now()
                        ) {

                            return 'Expired';

                        }

                        if (
                            $record->activeMembership->expires_at &&
                            $record->activeMembership->expires_at <= now()->addDays(7)
                        ) {

                            return 'Expiring Soon';

                        }

                        return 'Active';
                    })

                    ->badge()

                    ->color(function ($state) {

                        return match ($state) {

                            'Active' => 'success',

                            'Expiring Soon' => 'warning',

                            'Expired' => 'danger',

                            default => 'gray',

                        };
                    }),

                Tables\Columns\TextColumn::make('activeMembership.expires_at')

                    ->label('Expires')

                    ->date(),

                Tables\Columns\TextColumn::make('orders_count')
                    ->counts('orders')
                    ->label('Orders'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),

            ])

            ->filters([

                Tables\Filters\Filter::make('active_members')

                    ->query(fn ($query) =>

                        $query->whereHas('activeMembership', function ($q) {

                            $q->where('status', true)
                              ->where('expires_at', '>=', now());

                        })

                    ),

                Tables\Filters\Filter::make('expired_members')

                    ->query(fn ($query) =>

                        $query->whereHas('activeMembership', function ($q) {

                            $q->where('expires_at', '<', now());

                        })

                    ),

                Tables\Filters\Filter::make('expiring_soon')

                    ->query(fn ($query) =>

                        $query->whereHas('activeMembership', function ($q) {

                            $q->whereBetween('expires_at', [

                                now(),
                                now()->addDays(7),

                            ]);

                        })

                    ),

            ])

            ->actions([

                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('WhatsApp')

                    ->url(fn ($record) =>

                        $record->whatsapp_number
                            ? 'https://wa.me/' . $record->whatsapp_number
                            : null

                    )

                    ->openUrlInNewTab()

                    ->icon('heroicon-o-chat-bubble-left-right'),

            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PAGES
    |--------------------------------------------------------------------------
    */

    public static function getPages(): array
    {
        return [

            'index' => Pages\ListUsers::route('/'),

            'create' => Pages\CreateUser::route('/create'),

            'edit' => Pages\EditUser::route('/{record}/edit'),

        ];
    }
}