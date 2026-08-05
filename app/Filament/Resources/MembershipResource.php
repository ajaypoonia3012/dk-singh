<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MembershipResource\Pages;
use App\Models\Membership;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MembershipResource extends Resource
{
    protected static ?string $model = Membership::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Members';

    protected static ?string $navigationLabel = 'Memberships';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Membership')
                    ->schema([

                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('plan_id')
                            ->relationship('plan', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\DateTimePicker::make('starts_at')
                            ->required(),

                        Forms\Components\DateTimePicker::make('expires_at')
                            ->required(),

                        Forms\Components\Toggle::make('status')
                            ->label('Active')
                            ->default(true),

                    ])
                    ->columns(2),

                Forms\Components\Section::make('Subscription')
                    ->schema([

                        Forms\Components\Select::make('source')
                            ->options([
                                'admin' => 'Admin',
                                'stripe' => 'Stripe',
                                'razorpay' => 'Razorpay',
                            ])
                            ->default('admin')
                            ->required(),

                        Forms\Components\Toggle::make('auto_renew'),

                        Forms\Components\TextInput::make('payment_reference'),

                    ])
                    ->columns(3),

                Forms\Components\Section::make('Cancellation')
                    ->schema([

                        Forms\Components\DateTimePicker::make('cancelled_at'),

                        Forms\Components\Textarea::make('cancel_reason')
                            ->rows(3),

                        Forms\Components\Textarea::make('notes')
                            ->rows(4),

                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->defaultSort('expires_at')

            ->columns([

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Member')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('plan.name')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('source')
                    ->colors([
                        'primary' => 'admin',
                        'success' => 'stripe',
                        'warning' => 'razorpay',
                    ]),

                Tables\Columns\IconColumn::make('status')
                    ->label('Active')
                    ->boolean(),

                Tables\Columns\IconColumn::make('auto_renew')
                    ->boolean(),

                Tables\Columns\TextColumn::make('starts_at')
                    ->date(),

                Tables\Columns\TextColumn::make('expires_at')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('remaining_days')
                    ->label('Days Left')
                    ->state(fn (Membership $record) => $record->remainingDays()),

            ])

            ->filters([

                Tables\Filters\TernaryFilter::make('status'),

                Tables\Filters\TernaryFilter::make('auto_renew'),

                Tables\Filters\SelectFilter::make('source')
                    ->options([
                        'admin' => 'Admin',
                        'stripe' => 'Stripe',
                        'razorpay' => 'Razorpay',
                    ]),

            ])

            ->actions([

                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make(),

            ])

            ->bulkActions([

                Tables\Actions\DeleteBulkAction::make(),

            ]);
    }

    public static function getPages(): array
    {
        return [

            'index' => Pages\ListMemberships::route('/'),

            'create' => Pages\CreateMembership::route('/create'),

            'edit' => Pages\EditMembership::route('/{record}/edit'),

        ];
    }
}