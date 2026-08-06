<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Business';

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?int $navigationSort = 90;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make('Site configuration')
                ->persistTabInQueryString()
                ->columnSpanFull()
                ->tabs([
                    self::generalTab(),
                    self::contentTab(),
                    self::contactTab(),
                    self::navigationTab(),
                    self::seoTab(),
                    self::analyticsTab(),
                    self::footerTab(),
                    self::systemTab(),
                ]),
        ]);
    }

    private static function generalTab(): Tab
    {
        return Tab::make('General')
            ->icon('heroicon-o-building-office-2')
            ->schema([
                Section::make('Brand identity')
                    ->description('Core identity displayed in browser metadata, navigation, and the footer.')
                    ->icon('heroicon-o-swatch')
                    ->schema([
                        Forms\Components\TextInput::make('site_name')->required()->maxLength(100)
                            ->helperText('Public business or website name.'),
                        Forms\Components\TextInput::make('site_tagline')->maxLength(160)
                            ->helperText('Short positioning statement used near the logo.'),
                        Forms\Components\TextInput::make('business_niche')->maxLength(100),
                        Forms\Components\TextInput::make('business_display_name')->maxLength(150),
                        self::imageUpload('logo', 'Primary logo', 'Used on light backgrounds.'),
                        self::imageUpload('dark_logo', 'Dark logo', 'Used on dark backgrounds when available.'),
                        self::imageUpload('favicon', 'Favicon', 'Square PNG, WebP, or ICO recommended.'),
                        self::imageUpload('apple_touch_icon', 'Apple touch icon', 'Use a 180×180 PNG for iOS devices.'),
                    ])->columns(['default' => 1, 'md' => 2]),
                Section::make('Legal business identity')
                    ->description('Formal details used in invoices, legal pages, and compliance documents.')
                    ->icon('heroicon-o-scale')
                    ->collapsed()
                    ->schema([
                        Forms\Components\TextInput::make('legal_business_name')->maxLength(190),
                        Forms\Components\TextInput::make('tax_id')->label('Tax / GST identifier')->maxLength(100),
                    ])->columns(2),
            ]);
    }

    private static function contentTab(): Tab
    {
        return Tab::make('Content')
            ->icon('heroicon-o-document-text')
            ->schema([
                Section::make('Hero and introduction')
                    ->description('Primary homepage messaging and supporting imagery.')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Forms\Components\TextInput::make('hero_title')->maxLength(160),
                        Forms\Components\Textarea::make('hero_subtitle')->rows(3)->maxLength(500)->columnSpanFull(),
                        self::imageUpload('hero_image', 'Hero image', 'Large landscape image recommended.'),
                        Forms\Components\TextInput::make('hero_card_title')->maxLength(120),
                        Forms\Components\Textarea::make('hero_card_text')->rows(3)->maxLength(500),
                        Forms\Components\TextInput::make('about_title')->maxLength(160),
                        Forms\Components\Textarea::make('about_description')->rows(4)->maxLength(2000),
                        Forms\Components\Textarea::make('about_description_2')->rows(4)->maxLength(2000),
                        self::imageUpload('about_image', 'About image', 'Portrait or landscape image used in the About section.'),
                    ])->columns(['default' => 1, 'lg' => 2]),
                Section::make('Homepage headings and descriptions')
                    ->icon('heroicon-o-bars-3-bottom-left')
                    ->collapsed()
                    ->schema([
                        Forms\Components\TextInput::make('home_heading')->maxLength(160),
                        Forms\Components\TextInput::make('programs_heading')->maxLength(160),
                        Forms\Components\Textarea::make('programs_description')->rows(3)->maxLength(500),
                        Forms\Components\TextInput::make('services_heading')->maxLength(160),
                        Forms\Components\Textarea::make('services_description')->rows(3)->maxLength(500),
                        Forms\Components\TextInput::make('supplements_label')->maxLength(100),
                        Forms\Components\TextInput::make('supplements_heading')->maxLength(160),
                        Forms\Components\TextInput::make('transformations_heading')->maxLength(160),
                        Forms\Components\TextInput::make('testimonials_title')->maxLength(100),
                        Forms\Components\TextInput::make('testimonials_heading')->maxLength(160),
                        Forms\Components\Textarea::make('testimonials_description')->rows(3)->maxLength(500),
                        Forms\Components\TextInput::make('verified_client_label')->maxLength(100),
                    ])->columns(['default' => 1, 'lg' => 2]),
                Section::make('Statistics and BMI')
                    ->icon('heroicon-o-chart-bar')
                    ->collapsed()
                    ->schema([
                        Forms\Components\TextInput::make('instagram_followers')->maxLength(50),
                        Forms\Components\TextInput::make('years_experience')->maxLength(50),
                        Forms\Components\TextInput::make('transformations')->maxLength(50),
                        Forms\Components\TextInput::make('followers_label')->maxLength(100),
                        Forms\Components\TextInput::make('years_label')->maxLength(100),
                        Forms\Components\TextInput::make('transformations_label')->maxLength(100),
                        Forms\Components\TextInput::make('bmi_label')->maxLength(100),
                        Forms\Components\TextInput::make('bmi_heading')->maxLength(160),
                        Forms\Components\Textarea::make('bmi_description')->rows(3)->maxLength(500),
                    ])->columns(['default' => 1, 'md' => 3]),
                Section::make('Page introductions')
                    ->icon('heroicon-o-window')
                    ->collapsed()
                    ->schema([
                        Forms\Components\TextInput::make('services_page_label')->maxLength(100),
                        Forms\Components\Textarea::make('services_page_description')->rows(3)->maxLength(500),
                        Forms\Components\TextInput::make('programs_page_label')->maxLength(100),
                        Forms\Components\Textarea::make('programs_page_description')->rows(3)->maxLength(500),
                        Forms\Components\TextInput::make('transformations_page_label')->maxLength(100),
                        Forms\Components\TextInput::make('transformations_page_title')->maxLength(160),
                        Forms\Components\Textarea::make('transformations_page_description')->rows(3)->maxLength(500),
                    ])->columns(['default' => 1, 'lg' => 2]),
            ]);
    }

    private static function contactTab(): Tab
    {
        return Tab::make('Contact & Social')
            ->icon('heroicon-o-phone')
            ->schema([
                Section::make('Contact information')
                    ->description('Public contact details used across the website.')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Forms\Components\TextInput::make('phone')->tel()->maxLength(40),
                        Forms\Components\TextInput::make('whatsapp')->tel()->maxLength(40)
                            ->helperText('Include country code without spaces for WhatsApp links.'),
                        Forms\Components\TextInput::make('email')->email()->maxLength(190),
                        Forms\Components\TextInput::make('support_email')->email()->maxLength(190),
                        Forms\Components\Textarea::make('address')->rows(3)->maxLength(500)->columnSpanFull(),
                        Forms\Components\Textarea::make('working_hours')->rows(3)->maxLength(500),
                    ])->columns(['default' => 1, 'md' => 2]),
                Section::make('Contact page and map')
                    ->icon('heroicon-o-map-pin')
                    ->collapsed()
                    ->schema([
                        Forms\Components\TextInput::make('contact_title')->maxLength(120),
                        Forms\Components\TextInput::make('contact_heading')->maxLength(160),
                        Forms\Components\Textarea::make('contact_description')->rows(3)->maxLength(500),
                        Forms\Components\Textarea::make('contact_map_text')->rows(3)->maxLength(500),
                        Forms\Components\TextInput::make('map_embed_url')->url()->maxLength(2048),
                        Forms\Components\TextInput::make('map_link')->url()->maxLength(2048),
                    ])->columns(['default' => 1, 'lg' => 2]),
                Section::make('Social profiles')
                    ->icon('heroicon-o-share')
                    ->schema([
                        self::urlInput('facebook', 'Facebook profile URL'),
                        self::urlInput('instagram', 'Instagram profile URL'),
                        self::urlInput('youtube', 'YouTube channel URL'),
                        self::urlInput('twitter', 'X / Twitter profile URL'),
                    ])->columns(['default' => 1, 'md' => 2]),
            ]);
    }

    private static function navigationTab(): Tab
    {
        return Tab::make('Navigation')
            ->icon('heroicon-o-bars-3')
            ->schema([
                Section::make('Menu labels')
                    ->description('Change navigation wording without editing templates.')
                    ->schema([
                        ...self::textInputs([
                            'home_label' => 'Home', 'about_label' => 'About', 'contact_label' => 'Contact',
                            'plan_label' => 'Plans', 'program_label' => 'Programs', 'service_label' => 'Services',
                            'product_label' => 'Products', 'blog_label' => 'Blog',
                            'fitness_hub_label' => 'Fitness hub',
                            'transformation_label' => 'Transformations',
                        ]),
                    ])->columns(['default' => 1, 'md' => 3]),
                Section::make('Account labels')
                    ->collapsed()
                    ->schema([
                        ...self::textInputs([
                            'login_label' => 'Login', 'register_label' => 'Register',
                            'admin_panel_label' => 'Admin panel', 'my_plan_label' => 'My plan',
                            'my_orders_label' => 'My orders', 'logout_label' => 'Logout',
                        ]),
                    ])->columns(['default' => 1, 'md' => 3]),
                Section::make('Calls to action')
                    ->icon('heroicon-o-cursor-arrow-rays')
                    ->schema([
                        Forms\Components\TextInput::make('cta_button_text')->maxLength(80),
                        Forms\Components\TextInput::make('cta_button_link')->maxLength(2048),
                        Forms\Components\TextInput::make('about_cta_text')->maxLength(80),
                        Forms\Components\TextInput::make('contact_cta_text')->maxLength(80),
                        Forms\Components\TextInput::make('view_programs_text')->maxLength(80),
                        Forms\Components\TextInput::make('view_transformations_text')->maxLength(80),
                    ])->columns(['default' => 1, 'md' => 2]),
            ]);
    }

    private static function seoTab(): Tab
    {
        return Tab::make('SEO')
            ->icon('heroicon-o-magnifying-glass')
            ->schema([
                Section::make('Search and social metadata')
                    ->description('Defaults used when a page does not provide its own metadata.')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')->maxLength(60)
                            ->helperText('Recommended length: 50–60 characters.')
                            ->live(onBlur: true),
                        Forms\Components\Textarea::make('meta_description')->rows(4)->maxLength(160)
                            ->helperText('Recommended maximum: 160 characters.'),
                        Forms\Components\TagsInput::make('meta_keywords')
                            ->separator(',')
                            ->helperText('Optional. Press Enter after each phrase.'),
                    ]),
            ]);
    }

    private static function analyticsTab(): Tab
    {
        return Tab::make('Analytics')
            ->icon('heroicon-o-presentation-chart-line')
            ->schema([
                Section::make('Google integrations')
                    ->description('Identifiers only—never paste complete script tags.')
                    ->schema([
                        Forms\Components\TextInput::make('google_analytics_id')
                            ->placeholder('G-XXXXXXXXXX')->regex('/^G-[A-Z0-9]+$/i')->maxLength(30),
                        Forms\Components\TextInput::make('google_site_verification')->maxLength(255),
                    ])->columns(2),
            ]);
    }

    private static function footerTab(): Tab
    {
        return Tab::make('Footer')
            ->icon('heroicon-o-bars-arrow-down')
            ->schema([
                Section::make('Footer content')
                    ->schema([
                        Forms\Components\Textarea::make('footer_text')->rows(4)->maxLength(1000),
                        Forms\Components\TextInput::make('copyright_text')->maxLength(255)
                            ->helperText('Year and site name are added automatically when this is empty.'),
                        Forms\Components\TextInput::make('footer_links_heading')->maxLength(100),
                        Forms\Components\TextInput::make('footer_services_heading')->maxLength(100),
                    ])->columns(['default' => 1, 'md' => 2]),
            ]);
    }

    private static function systemTab(): Tab
    {
        return Tab::make('System')
            ->icon('heroicon-o-server-stack')
            ->schema([
                Section::make('Maintenance')
                    ->description('Stores the maintenance message for controlled activation by deployment tooling.')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->schema([
                        Forms\Components\Toggle::make('maintenance_enabled')->inline(false),
                        Forms\Components\Textarea::make('maintenance_message')->rows(4)->maxLength(1000),
                    ]),
            ]);
    }

    private static function imageUpload(string $name, string $label, string $help): Forms\Components\FileUpload
    {
        return Forms\Components\FileUpload::make($name)
            ->label($label)
            ->helperText($help)
            ->disk('public')
            ->directory('settings')
            ->image()
            ->imagePreviewHeight('120')
            ->openable()
            ->downloadable();
    }

    private static function urlInput(string $name, string $label): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make($name)->label($label)->url()->maxLength(2048);
    }

    /** @return array<Forms\Components\TextInput> */
    private static function textInputs(array $fields): array
    {
        return collect($fields)
            ->map(fn (string $label, string $name) => Forms\Components\TextInput::make($name)
                ->label($label)
                ->maxLength(100))
            ->values()
            ->all();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')->disk('public')->label('Logo'),
                Tables\Columns\TextColumn::make('site_name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('updated_at')->since()->label('Last updated'),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function canCreate(): bool
    {
        return parent::canCreate() && ! Setting::query()->exists();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
