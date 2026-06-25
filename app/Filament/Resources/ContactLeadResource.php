<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactLeadResource\Pages;
use App\Models\ContactLead;

use Filament\Forms;
use Filament\Forms\Form;

use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;

class ContactLeadResource extends Resource
{
    protected static ?string $model = ContactLead::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'CRM';

    protected static ?string $navigationLabel = 'Inquiries';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Lead Information')
                    ->schema([

                        Forms\Components\TextInput::make('name')
                            ->required(),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required(),

                        Forms\Components\TextInput::make('phone'),

                        Forms\Components\Textarea::make('message')
                            ->rows(6),

                        Forms\Components\Select::make('status')
                            ->options([
                                'new' => 'New',
                                'contacted' => 'Contacted',
                                'interested' => 'Interested',
                                'converted' => 'Converted',
                                'not_interested' => 'Not Interested',
                            ])
                            ->default('new')
                            ->required(),

                        Forms\Components\Select::make('priority')
                            ->options([
                                'low' => 'Low',
                                'medium' => 'Medium',
                                'high' => 'High',
                            ])
                            ->default('medium')
                            ->required(),

                        Forms\Components\TextInput::make('source')
                            ->placeholder('Instagram, Website, WhatsApp etc.'),

                        Forms\Components\DatePicker::make('follow_up_date'),

                        Forms\Components\Textarea::make('notes')
                            ->rows(5)
                            ->columnSpanFull(),

                    ])
                    ->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'primary' => 'new',
                        'warning' => 'contacted',
                        'info' => 'interested',
                        'success' => 'converted',
                        'danger' => 'not_interested',
                    ]),

                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->colors([
                        'success' => 'low',
                        'warning' => 'medium',
                        'danger' => 'high',
                    ]),

                Tables\Columns\TextColumn::make('source')
                    ->searchable(),

                Tables\Columns\TextColumn::make('notes')
                    ->limit(30)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('follow_up_date')
                    ->date(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y - h:i A')
                    ->sortable(),

            ])

            ->filters([

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'interested' => 'Interested',
                        'converted' => 'Converted',
                        'not_interested' => 'Not Interested',
                    ]),

                Tables\Filters\SelectFilter::make('priority')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                    ]),

            ])

            ->actions([

                Tables\Actions\ViewAction::make(),

                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(fn ($record) =>
                        'https://wa.me/' . preg_replace('/[^0-9]/', '', $record->phone)
                    )
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('call')
                    ->label('Call')
                    ->icon('heroicon-o-phone')
                    ->color('primary')
                    ->url(fn ($record) =>
                        'tel:' . $record->phone
                    ),

                Tables\Actions\Action::make('email')
                    ->label('Email')
                    ->icon('heroicon-o-envelope')
                    ->color('warning')
                    ->url(fn ($record) =>
                        'mailto:' . $record->email
                    ),

                Tables\Actions\DeleteAction::make(),

            ])

            ->bulkActions([

                Tables\Actions\DeleteBulkAction::make(),

            ])

            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [

            'index' => Pages\ListContactLeads::route('/'),

            'create' => Pages\CreateContactLead::route('/create'),

            'edit' => Pages\EditContactLead::route('/{record}/edit'),

        ];
    }
}