<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomepageCardResource\Pages;
use App\Models\HomepageCard;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HomepageCardResource extends Resource
{
    protected static ?string $model = HomepageCard::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Homepage Cards';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Card Content')
                    ->schema([

                        Forms\Components\TextInput::make('title')
                            ->required(),

                        Forms\Components\TextInput::make('subtitle'),

                        Forms\Components\Textarea::make('description')
                            ->rows(4)
                            ->columnSpanFull(),

                    ])
                    ->columns(2),

                Forms\Components\Section::make('Button')
                    ->schema([

                        Forms\Components\TextInput::make('button_text')
                            ->placeholder('Learn More'),

                        Forms\Components\TextInput::make('button_link')
                            ->placeholder('/plans'),

                    ])
                    ->columns(2),

                Forms\Components\Section::make('Appearance')
                    ->schema([

                        Forms\Components\TextInput::make('icon')
                            ->helperText('Example: 💪 🔥 🏆 ❤️ ⭐'),

                        Forms\Components\FileUpload::make('background_image')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                            ])
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth('1200')
                            ->imageResizeTargetHeight('675')
                            ->disk('public')
                            ->directory('homepage-cards')
                            ->imagePreviewHeight('220'),

                    ])
                    ->columns(2),

                Forms\Components\Section::make('Publishing')
                    ->schema([

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),

                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),

                    ])
                    ->columns(2),

                Forms\Components\Section::make('SEO')
                    ->schema([

                        Forms\Components\TextInput::make('seo_title'),

                        Forms\Components\Textarea::make('seo_description')
                            ->rows(4),

                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\ImageColumn::make('background_image')
                    ->disk('public')
                    ->square()
                    ->height(60),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('subtitle')
                    ->limit(30),

                Tables\Columns\TextColumn::make('icon'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),

            ])

            ->filters([

                Tables\Filters\TernaryFilter::make('is_active'),

            ])

            ->actions([

                Tables\Actions\EditAction::make(),

            ])

            ->bulkActions([

                Tables\Actions\BulkActionGroup::make([

                    Tables\Actions\DeleteBulkAction::make(),

                ]),

            ])

            ->defaultSort('sort_order');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [

            'index' => Pages\ListHomepageCards::route('/'),

            'create' => Pages\CreateHomepageCard::route('/create'),

            'edit' => Pages\EditHomepageCard::route('/{record}/edit'),

        ];
    }
}