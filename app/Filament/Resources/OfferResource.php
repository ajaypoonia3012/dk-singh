<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OfferResource\Pages;
use App\Models\Offer;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class OfferResource extends Resource
{
    protected static ?string $model = Offer::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Commerce';

    protected static ?string $navigationLabel = 'Offers & Discounts';

    protected static ?string $modelLabel = 'Offer';

    protected static ?string $pluralModelLabel = 'Offers & Discounts';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('General Offer Details')
                            ->description('Basic identification, promotional code, and messaging.')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Offer Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g. Summer Fitness Discount'),

                                Forms\Components\TextInput::make('code')
                                    ->label('Promo Code')
                                    ->required()
                                    ->maxLength(50)
                                    ->unique(ignoreRecord: true)
                                    ->dehydrateStateUsing(fn (?string $state) => Str::upper(trim((string) $state)))
                                    ->placeholder('e.g. SUMMER15')
                                    ->helperText('Case-insensitive promo code entered by customers at checkout.'),

                                Forms\Components\Textarea::make('description')
                                    ->label('Internal / Customer Description')
                                    ->rows(3)
                                    ->maxLength(1000)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Discount Rules & Pricing')
                            ->description('Configure the discount percentage or flat amount and constraints.')
                            ->schema([
                                Forms\Components\Select::make('discount_type')
                                    ->label('Discount Type')
                                    ->required()
                                    ->options([
                                        Offer::TYPE_PERCENTAGE => 'Percentage (%) Off',
                                        Offer::TYPE_FIXED => 'Flat Amount (₹) Off',
                                    ])
                                    ->default(Offer::TYPE_PERCENTAGE)
                                    ->live(),

                                Forms\Components\TextInput::make('discount_value')
                                    ->label('Discount Value')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0.01)
                                    ->prefix(fn (Get $get) => $get('discount_type') === Offer::TYPE_PERCENTAGE ? '%' : '₹')
                                    ->helperText('For percentage, enter rate (e.g. 15 for 15%). For flat, enter ₹ amount.'),

                                Forms\Components\TextInput::make('max_discount_cap')
                                    ->label('Maximum Discount Cap (₹)')
                                    ->numeric()
                                    ->minValue(0.01)
                                    ->prefix('₹')
                                    ->visible(fn (Get $get) => $get('discount_type') === Offer::TYPE_PERCENTAGE)
                                    ->helperText('Optional upper limit for percentage discounts (e.g. max ₹500 discount).'),

                                Forms\Components\TextInput::make('min_order_value')
                                    ->label('Minimum Order Value (₹)')
                                    ->numeric()
                                    ->minValue(0)
                                    ->prefix('₹')
                                    ->helperText('Minimum product subtotal required for this offer to become eligible.'),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Product Applicability')
                            ->description('Control which catalog products qualify for this promotion.')
                            ->schema([
                                Forms\Components\Radio::make('applicable_products_type')
                                    ->label('Eligible Products')
                                    ->options([
                                        'all' => 'All Products (Storewide)',
                                        'specific' => 'Selected Products Only',
                                    ])
                                    ->default('all')
                                    ->live(),

                                Forms\Components\Select::make('products')
                                    ->relationship('products', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->searchable()
                                    ->visible(fn (Get $get) => $get('applicable_products_type') === 'specific')
                                    ->helperText('Select the specific products eligible for this discount.'),
                            ]),

                        Forms\Components\Section::make('Promotional Banner Copy')
                            ->description('Optional marketing headline and call-to-action shown on site banners.')
                            ->schema([
                                Forms\Components\TextInput::make('banner_headline')
                                    ->label('Banner Headline')
                                    ->maxLength(255)
                                    ->placeholder('e.g. FLASH SALE — 15% OFF SUPPLEMENTS'),

                                Forms\Components\TextInput::make('banner_cta')
                                    ->label('Banner Button CTA')
                                    ->maxLength(100)
                                    ->placeholder('e.g. Claim Discount'),

                                Forms\Components\Textarea::make('banner_description')
                                    ->label('Banner Description')
                                    ->rows(2)
                                    ->maxLength(500)
                                    ->placeholder('e.g. Use code at checkout for immediate savings.')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->collapsed(),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Status & Visibility')
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label('Saved Status')
                                    ->required()
                                    ->options([
                                        Offer::STATUS_ACTIVE => 'Active (Ready / Live)',
                                        Offer::STATUS_DRAFT => 'Draft (Hidden)',
                                        Offer::STATUS_DISABLED => 'Disabled (Inactive)',
                                    ])
                                    ->default(Offer::STATUS_ACTIVE),

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Featured Offer')
                                    ->helperText('Show prominently in the sitewide promotional banner. Only one offer can be featured at a time.'),

                                Forms\Components\TextInput::make('priority')
                                    ->label('Display Priority')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Higher values are displayed first to customers.'),

                                Forms\Components\Toggle::make('allow_stacking')
                                    ->label('Allow Stacking')
                                    ->default(false)
                                    ->helperText('Default is one discount per order with no stacking.'),
                            ]),

                        Forms\Components\Section::make('Schedule')
                            ->schema([
                                Forms\Components\DateTimePicker::make('starts_at')
                                    ->label('Start Date & Time')
                                    ->helperText('Leave empty for immediate activation.'),

                                Forms\Components\DateTimePicker::make('ends_at')
                                    ->label('End Date & Time')
                                    ->afterOrEqual('starts_at')
                                    ->helperText('Leave empty for an ongoing offer.'),
                            ]),

                        Forms\Components\Section::make('Redemption Limits')
                            ->schema([
                                Forms\Components\TextInput::make('max_redemptions')
                                    ->label('Total Redemption Limit')
                                    ->numeric()
                                    ->minValue(1)
                                    ->helperText('Maximum total times this offer can be redeemed across all customers.'),

                                Forms\Components\TextInput::make('max_redemptions_per_customer')
                                    ->label('Per-Customer Limit')
                                    ->numeric()
                                    ->minValue(1)
                                    ->default(1)
                                    ->helperText('Maximum times an individual customer can use this offer.'),

                                Forms\Components\Placeholder::make('total_redemptions_display')
                                    ->label('Current Successful Redemptions')
                                    ->content(fn (?Offer $record) => $record ? (string) $record->total_redemptions : '0'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Promo Code')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('discount_display')
                    ->label('Discount')
                    ->state(function (Offer $record): string {
                        if ($record->discount_type === Offer::TYPE_PERCENTAGE) {
                            $cap = $record->max_discount_cap ? ' (Cap ₹'.number_format($record->max_discount_cap, 2).')' : '';

                            return rtrim(rtrim((string) $record->discount_value, '0'), '.').'% off'.$cap;
                        }

                        return '₹'.number_format((float) $record->discount_value, 2).' off';
                    }),

                Tables\Columns\TextColumn::make('computed_status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'active',
                        'warning' => 'scheduled',
                        'danger' => 'expired',
                        'gray' => 'disabled',
                        'info' => 'draft',
                        'primary' => 'exhausted',
                    ])
                    ->formatStateUsing(fn (string $state) => ucfirst($state)),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),

                Tables\Columns\TextColumn::make('redemptions_ratio')
                    ->label('Redeemed')
                    ->state(fn (Offer $record) => sprintf(
                        '%d%s',
                        $record->total_redemptions,
                        $record->max_redemptions ? ' / '.$record->max_redemptions : ''
                    )),

                Tables\Columns\TextColumn::make('priority')
                    ->label('Priority')
                    ->sortable(),

                Tables\Columns\TextColumn::make('starts_at')
                    ->label('Starts')
                    ->dateTime('M d, Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('ends_at')
                    ->label('Ends')
                    ->dateTime('M d, Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('priority', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        Offer::STATUS_ACTIVE => 'Active',
                        Offer::STATUS_DRAFT => 'Draft',
                        Offer::STATUS_DISABLED => 'Disabled',
                    ]),
                Tables\Filters\SelectFilter::make('discount_type')
                    ->options([
                        Offer::TYPE_PERCENTAGE => 'Percentage',
                        Offer::TYPE_FIXED => 'Flat Amount',
                    ]),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Tables\Actions\DeleteAction $action, Offer $record) {
                        if ($record->orders()->exists()) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Cannot Delete Offer')
                                ->body('This offer is linked to customer orders. Deactivate it instead.')
                                ->send();

                            $action->halt();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOffers::route('/'),
            'create' => Pages\CreateOffer::route('/create'),
            'edit' => Pages\EditOffer::route('/{record}/edit'),
        ];
    }
}
