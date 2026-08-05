<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WebsiteSectionResource\Pages;
use App\Models\WebsiteSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WebsiteSectionResource extends Resource
{
    protected static ?string $model = WebsiteSection::class;

protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationGroup = 'Website Builder';

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationLabel = 'Homepage Builder';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Section')

                    ->schema([

                        Forms\Components\TextInput::make('title')
                            ->required(),

                        Forms\Components\Select::make('page')
                            ->options([
                                'home' => 'Homepage',
                                'about' => 'About',
                                'contact' => 'Contact',
                                'plans' => 'Plans',
                                'products' => 'Products',
                            ])
                            ->default('home'),

                        Forms\Components\Select::make('section')
                            ->options([

                                'hero' => 'Hero',

                                'homepage_cards' => 'Homepage Cards',

                                'programs' => 'Programs',

                                'products' => 'Products',

                                'transformations' => 'Transformations',

                                'testimonials' => 'Testimonials',

                                'blogs' => 'Blogs',

                                'contact' => 'Contact',

                            ])
                            ->required(),

                        Forms\Components\Toggle::make('enabled')
                            ->default(true),

                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(1),

                    ]),

                Forms\Components\Section::make('Appearance')

                    ->schema([

                        Forms\Components\FileUpload::make('background')
                            ->directory('website'),

                        Forms\Components\Select::make('template')

                            ->options([

                                'default' => 'Default',

                                'modern' => 'Modern',

                                'dark' => 'Dark',

                                'luxury' => 'Luxury',

                            ])
                            ->default('default'),

                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->reorderable('sort_order')

            ->defaultSort('sort_order')

            ->columns([

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),

                Tables\Columns\TextColumn::make('title'),

                Tables\Columns\BadgeColumn::make('page'),

                Tables\Columns\BadgeColumn::make('section'),

                Tables\Columns\BadgeColumn::make('template'),

                Tables\Columns\IconColumn::make('enabled')
                    ->boolean(),

            ])

            ->actions([

                Tables\Actions\EditAction::make(),

            ]);
    }

    public static function getPages(): array
    {
        return [

            'index' => Pages\ListWebsiteSections::route('/'),

            'create' => Pages\CreateWebsiteSection::route('/create'),

            'edit' => Pages\EditWebsiteSection::route('/{record}/edit'),

        ];
    }
}