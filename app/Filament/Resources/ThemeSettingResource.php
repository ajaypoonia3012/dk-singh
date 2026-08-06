<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ThemeSettingResource\Pages;
use App\Models\ThemeSetting;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ThemeSettingResource extends Resource
{
    protected static ?string $model = ThemeSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';

    protected static ?string $navigationGroup = 'Business';

    protected static ?string $navigationLabel = 'Design System';

    protected static ?int $navigationSort = 91;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make('Design system')
                ->persistTabInQueryString()
                ->columnSpanFull()
                ->tabs([
                    self::identityTab(),
                    self::typographyTab(),
                    self::componentsTab(),
                    self::layoutTab(),
                    self::homepageTab(),
                    self::engagementTab(),
                    self::motionTab(),
                    self::advancedTab(),
                ]),
        ]);
    }

    private static function identityTab(): Tab
    {
        return Tab::make('Colors')
            ->icon('heroicon-o-paint-brush')
            ->schema([
                Section::make('Theme identity')
                    ->description('Name this design-system configuration for administrators.')
                    ->schema([
                        Forms\Components\TextInput::make('theme_name')->required()->maxLength(100),
                    ]),
                Section::make('Brand colors')
                    ->description('Shared semantic colors exposed as CSS variables on every frontend page.')
                    ->icon('heroicon-o-swatch')
                    ->schema([
                        self::color('primary_color', 'Primary', '#facc15'),
                        self::color('secondary_color', 'Secondary', '#111111'),
                        self::color('accent_color', 'Accent / page background', '#ffffff'),
                        self::color('success_color', 'Success', '#22c55e'),
                        self::color('warning_color', 'Warning', '#f59e0b'),
                        self::color('danger_color', 'Danger', '#ef4444'),
                        self::color('info_color', 'Information', '#3b82f6'),
                        self::color('neutral_color', 'Neutral', '#6b7280'),
                    ])->columns(['default' => 1, 'md' => 2, 'xl' => 4]),
                Section::make('Dark mode palette')
                    ->icon('heroicon-o-moon')
                    ->collapsed()
                    ->schema([
                        Forms\Components\Toggle::make('dark_mode_enabled')->helperText('Applies dark mode by default.'),
                        Forms\Components\Toggle::make('dark_mode_toggle')->helperText('Reserves support for a visitor-facing toggle.'),
                        self::color('dark_background', 'Dark background', '#111111'),
                        self::color('dark_surface', 'Dark surface', '#1f2937'),
                        self::color('dark_text', 'Dark text', '#f9fafb'),
                    ])->columns(['default' => 1, 'md' => 2]),
            ]);
    }

    private static function typographyTab(): Tab
    {
        return Tab::make('Typography')
            ->icon('heroicon-o-language')
            ->schema([
                Section::make('Font families')
                    ->description('Use an installed web-safe or externally loaded font family name.')
                    ->schema([
                        Forms\Components\TextInput::make('heading_font')->required()->maxLength(100)->placeholder('Poppins'),
                        Forms\Components\TextInput::make('body_font')->required()->maxLength(100)->placeholder('Poppins'),
                    ])->columns(2),
                Section::make('Type scale')
                    ->schema([
                        Forms\Components\TextInput::make('font_scale')->numeric()->minValue(0.75)->maxValue(2)->step(0.05)->required(),
                        Forms\Components\Select::make('heading_weight')->options(self::fontWeights())->required(),
                        Forms\Components\Select::make('body_weight')->options(self::fontWeights())->required(),
                        Forms\Components\TextInput::make('letter_spacing')->numeric()->minValue(-2)->maxValue(10)->step(0.1)->suffix('px'),
                        Forms\Components\TextInput::make('line_height')->numeric()->minValue(1)->maxValue(3)->step(0.05)->required(),
                    ])->columns(['default' => 1, 'md' => 3]),
            ]);
    }

    private static function componentsTab(): Tab
    {
        return Tab::make('Components')
            ->icon('heroicon-o-squares-2x2')
            ->schema([
                Section::make('Buttons')
                    ->icon('heroicon-o-cursor-arrow-rays')
                    ->schema([
                        self::color('primary_button_text_color', 'Primary button text', '#111111'),
                        self::color('secondary_button_background', 'Secondary button background', '#111111'),
                        self::color('secondary_button_text_color', 'Secondary button text', '#ffffff'),
                        self::token('button_radius', 'Border radius', '1rem'),
                        self::token('button_shadow', 'Shadow', '0 10px 25px rgba(0,0,0,.12)'),
                        self::token('button_hover_animation', 'Hover transform', 'translateY(-2px)'),
                    ])->columns(['default' => 1, 'md' => 2]),
                Section::make('Cards')
                    ->icon('heroicon-o-square-3-stack-3d')
                    ->collapsed()
                    ->schema([
                        self::color('card_background', 'Background', '#ffffff'),
                        self::token('card_radius', 'Border radius', '1.5rem'),
                        self::token('card_shadow', 'Shadow', '0 20px 40px rgba(0,0,0,.08)'),
                    ])->columns(3),
                Section::make('Forms')
                    ->icon('heroicon-o-pencil-square')
                    ->collapsed()
                    ->schema([
                        self::token('input_radius', 'Input radius', '1rem'),
                        self::color('input_border_color', 'Border color', '#d1d5db'),
                        self::color('input_focus_color', 'Focus color', '#facc15'),
                    ])->columns(3),
            ]);
    }

    private static function layoutTab(): Tab
    {
        return Tab::make('Layout')
            ->icon('heroicon-o-rectangle-group')
            ->schema([
                Section::make('Global dimensions')
                    ->description('Pixel values are constrained to production-safe ranges.')
                    ->schema([
                        self::number('container_width', 'Container width', 960, 1920, 'px'),
                        self::number('section_padding', 'Section padding', 24, 240, 'px'),
                        Forms\Components\TextInput::make('spacing_scale')->numeric()->minValue(0.5)->maxValue(3)->step(0.05)->required(),
                        self::number('sidebar_width', 'Sidebar width', 240, 600, 'px'),
                    ])->columns(['default' => 1, 'md' => 2]),
                Section::make('Navbar')
                    ->icon('heroicon-o-bars-3')
                    ->schema([
                        self::number('navbar_height', 'Height', 56, 160, 'px'),
                        self::color('navbar_background', 'Background', '#ffffff'),
                        self::color('navbar_text_color', 'Text', '#111111'),
                        self::color('navbar_hover_color', 'Hover', '#facc15'),
                    ])->columns(['default' => 1, 'md' => 2]),
                Section::make('Hero and footer')
                    ->collapsed()
                    ->schema([
                        self::color('hero_overlay_color', 'Hero overlay', '#000000'),
                        Forms\Components\TextInput::make('hero_overlay_opacity')->numeric()->minValue(0)->maxValue(100)->suffix('%'),
                        Forms\Components\TextInput::make('hero_gradient')->maxLength(500)->placeholder('linear-gradient(...)'),
                        self::color('footer_background', 'Footer background', '#000000'),
                        self::color('footer_text_color', 'Footer text', '#ffffff'),
                        self::color('footer_link_color', 'Footer links', '#9ca3af'),
                    ])->columns(['default' => 1, 'md' => 3]),
            ]);
    }

    private static function homepageTab(): Tab
    {
        return Tab::make('Sections')
            ->icon('heroicon-o-eye')
            ->schema([
                Section::make('Homepage visibility')
                    ->description('Enable or disable existing homepage sections without changing their content.')
                    ->schema([
                        ...collect([
                            'show_hero' => 'Hero', 'show_programs' => 'Programs', 'show_services' => 'Services',
                            'show_products' => 'Products', 'show_blogs' => 'Blog', 'show_transformations' => 'Transformations',
                            'show_plans' => 'Plans', 'show_about' => 'About', 'show_bmi' => 'BMI calculator',
                            'show_homepage_cards' => 'Homepage cards', 'show_testimonials' => 'Testimonials',
                            'show_contact' => 'Contact',
                        ])->map(fn (string $label, string $name) => Forms\Components\Toggle::make($name)
                            ->label($label)
                            ->default(true)
                            ->inline(false))
                            ->values()
                            ->all(),
                    ])->columns(['default' => 1, 'md' => 3, 'xl' => 4]),
                Section::make('Public counters')
                    ->collapsed()
                    ->schema([
                        self::number('clients_count', 'Clients', 0, 10000000),
                        self::number('coached_count', 'People coached', 0, 10000000),
                        self::number('programs_count', 'Programs', 0, 10000000),
                        self::number('countries_count', 'Countries', 0, 10000000),
                    ])->columns(['default' => 1, 'md' => 4]),
            ]);
    }

    private static function engagementTab(): Tab
    {
        return Tab::make('Engagement')
            ->icon('heroicon-o-megaphone')
            ->schema([
                Section::make('Announcement bar')
                    ->schema([
                        Forms\Components\Toggle::make('announcement_enabled')->live()->inline(false),
                        Forms\Components\Textarea::make('announcement_text')->rows(3)->maxLength(500),
                        Forms\Components\TextInput::make('announcement_link')->url()->maxLength(2048),
                    ]),
                Section::make('Promotional popup')
                    ->collapsed()
                    ->schema([
                        Forms\Components\Toggle::make('popup_enabled')->live()->inline(false),
                        Forms\Components\TextInput::make('popup_title')->maxLength(160),
                        Forms\Components\Textarea::make('popup_description')->rows(4)->maxLength(1000),
                        Forms\Components\TextInput::make('popup_button_text')->maxLength(80),
                        Forms\Components\TextInput::make('popup_button_link')->maxLength(2048),
                        Forms\Components\FileUpload::make('popup_image')
                            ->disk('public')->directory('theme-popup')->image()->imagePreviewHeight('120')->openable(),
                    ])->columns(['default' => 1, 'md' => 2]),
            ]);
    }

    private static function motionTab(): Tab
    {
        return Tab::make('Motion')
            ->icon('heroicon-o-bolt')
            ->schema([
                Section::make('Animation preferences')
                    ->description('These flags expose consistent frontend behavior without altering page content.')
                    ->schema([
                        Forms\Components\Toggle::make('animations_enabled')->default(true)->inline(false),
                        Forms\Components\Toggle::make('scroll_reveal_enabled')->default(true)->inline(false),
                        Forms\Components\Toggle::make('page_loader_enabled')->default(false)->inline(false),
                    ])->columns(3),
            ]);
    }

    private static function advancedTab(): Tab
    {
        return Tab::make('Advanced')
            ->icon('heroicon-o-code-bracket')
            ->schema([
                Section::make('Custom CSS')
                    ->description('Trusted administrators only. Enter declarations without a <style> tag.')
                    ->schema([
                        Forms\Components\Textarea::make('custom_css')->rows(14)->maxLength(50000)
                            ->extraAttributes(['class' => 'font-mono']),
                    ]),
                Section::make('Custom JavaScript')
                    ->description('Trusted administrators only. Enter JavaScript without a <script> tag.')
                    ->collapsed()
                    ->schema([
                        Forms\Components\Textarea::make('custom_js')->rows(14)->maxLength(50000)
                            ->extraAttributes(['class' => 'font-mono']),
                    ]),
            ]);
    }

    private static function color(string $name, string $label, string $default): Forms\Components\ColorPicker
    {
        return Forms\Components\ColorPicker::make($name)
            ->label($label)
            ->default($default)
            ->required();
    }

    private static function token(string $name, string $label, string $placeholder): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make($name)
            ->label($label)
            ->placeholder($placeholder)
            ->maxLength(255)
            ->required();
    }

    private static function number(
        string $name,
        string $label,
        int $minimum,
        int $maximum,
        ?string $suffix = null
    ): Forms\Components\TextInput {
        return Forms\Components\TextInput::make($name)
            ->label($label)
            ->numeric()
            ->minValue($minimum)
            ->maxValue($maximum)
            ->suffix($suffix)
            ->required();
    }

    private static function fontWeights(): array
    {
        return [300 => 'Light', 400 => 'Regular', 500 => 'Medium', 600 => 'Semibold', 700 => 'Bold', 800 => 'Extra bold', 900 => 'Black'];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('theme_name')->searchable(),
                Tables\Columns\ColorColumn::make('primary_color')->label('Primary'),
                Tables\Columns\ColorColumn::make('secondary_color')->label('Secondary'),
                Tables\Columns\IconColumn::make('dark_mode_enabled')->boolean()->label('Dark mode'),
                Tables\Columns\TextColumn::make('updated_at')->since()->label('Last updated'),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function canCreate(): bool
    {
        return parent::canCreate() && ! ThemeSetting::query()->exists();
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
