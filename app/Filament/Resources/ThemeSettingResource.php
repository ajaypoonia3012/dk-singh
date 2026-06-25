<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ThemeSettingResource\Pages;
use App\Filament\Resources\ThemeSettingResource\RelationManagers;
use App\Models\ThemeSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ThemeSettingResource extends Resource
{
    protected static ?string $model = ThemeSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form
        ->schema([

            Forms\Components\Section::make('Theme Colors')
                ->schema([

                    Forms\Components\TextInput::make('primary_color')
                        ->placeholder('#facc15'),

                    Forms\Components\TextInput::make('secondary_color')
                        ->placeholder('#000000'),

                    Forms\Components\TextInput::make('accent_color')
                        ->placeholder('#ffffff'),

                    Forms\Components\TextInput::make('theme_name'),

                ]),

            Forms\Components\Section::make('Homepage Sections')
                ->schema([

                    Forms\Components\Toggle::make('show_programs'),

                    Forms\Components\Toggle::make('show_services'),

                    Forms\Components\Toggle::make('show_products'),

                    Forms\Components\Toggle::make('show_blogs'),

                    Forms\Components\Toggle::make('show_transformations'),

                    Forms\Components\Toggle::make('show_plans'),

                ]),

            Forms\Components\Section::make('Announcement Bar')
                ->schema([

                    Forms\Components\Toggle::make('announcement_enabled'),

                    Forms\Components\Textarea::make('announcement_text'),

                    Forms\Components\TextInput::make('announcement_link'),

                ]),

            Forms\Components\Section::make('Popup Settings')
                ->schema([

                    Forms\Components\Toggle::make('popup_enabled'),

                    Forms\Components\TextInput::make('popup_title'),

                    Forms\Components\Textarea::make('popup_description'),

                    Forms\Components\TextInput::make('popup_button_text'),

                    Forms\Components\TextInput::make('popup_button_link'),

                    Forms\Components\FileUpload::make('popup_image')
                        ->directory('theme-popup'),

                ]),

           
Forms\Components\Section::make('Counters')
    ->schema([

        Forms\Components\TextInput::make('clients_count')
            ->numeric()
            ->default(0)
            ->required(),

        Forms\Components\TextInput::make('coached_count')
            ->numeric()
            ->default(0)
            ->required(),

        Forms\Components\TextInput::make('programs_count')
            ->numeric()
            ->default(0)
            ->required(),

        Forms\Components\TextInput::make('countries_count')
            ->numeric()
            ->default(0)
            ->required(),

    ]),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

    Tables\Columns\TextColumn::make('theme_name'),

    Tables\Columns\IconColumn::make('show_programs')
        ->boolean(),

    Tables\Columns\IconColumn::make('show_services')
        ->boolean(),

    Tables\Columns\IconColumn::make('show_products')
        ->boolean(),

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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListThemeSettings::route('/'),
            'create' => Pages\CreateThemeSetting::route('/create'),
            'edit' => Pages\EditThemeSetting::route('/{record}/edit'),
        ];
    }
}
