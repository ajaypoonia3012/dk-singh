<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;

use Filament\Forms;
use Filament\Forms\Form;

use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Business';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Hero Section')
                    ->schema([
			
			Forms\Components\TextInput::make('about_title'), 

			Forms\Components\Textarea::make('about_description'), 
			
			Forms\Components\Textarea::make('about_description_2'), 

		Forms\Components\FileUpload::make('about_image')
    ->disk('public')
    ->directory('settings'),

                        Forms\Components\TextInput::make('hero_title'),

                        Forms\Components\Textarea::make('hero_subtitle'),

                        Forms\Components\FileUpload::make('hero_image')
                            ->directory('settings'),

                    ]),

                Forms\Components\Section::make('Statistics')
                    ->schema([

                        Forms\Components\TextInput::make('instagram_followers'),

                        Forms\Components\TextInput::make('years_experience'),

                        Forms\Components\TextInput::make('transformations'),




                    ]),

                Forms\Components\Section::make('CTA Buttons')
                    ->schema([

                        Forms\Components\TextInput::make('cta_button_text'),

                        Forms\Components\TextInput::make('cta_button_link'),

                    ]),

                Forms\Components\Section::make('Branding')
                    ->schema([

                        Forms\Components\TextInput::make('site_name'),

                        Forms\Components\TextInput::make('site_tagline'),

                        Forms\Components\FileUpload::make('logo')
                            ->directory('settings'),

                        Forms\Components\FileUpload::make('favicon')
                            ->directory('settings'),

                    ]),

                Forms\Components\Section::make('Contact Information')
                    ->schema([

                        Forms\Components\TextInput::make('phone'),

                        Forms\Components\TextInput::make('email'),

                        Forms\Components\TextInput::make('whatsapp'),

                        Forms\Components\Textarea::make('address'),
Forms\Components\TextInput::make('blog_label'),

Forms\Components\TextInput::make('product_label'),

Forms\Components\TextInput::make('service_label'),

Forms\Components\TextInput::make('program_label'),

Forms\Components\TextInput::make('business_niche'),

Forms\Components\Textarea::make('working_hours'),

Forms\Components\TextInput::make('business_display_name'),

Forms\Components\Textarea::make('map_embed_url'),

Forms\Components\Textarea::make('map_link'),

                    ]),

                Forms\Components\Section::make('Social Media')
                    ->schema([

                        Forms\Components\TextInput::make('facebook'),

                        Forms\Components\TextInput::make('instagram'),

                        Forms\Components\TextInput::make('youtube'),

                        Forms\Components\TextInput::make('twitter'),

                    ]),

                Forms\Components\Section::make('SEO')
                    ->schema([

                        Forms\Components\TextInput::make('meta_title'),

                        Forms\Components\Textarea::make('meta_description'),

                        Forms\Components\Textarea::make('meta_keywords'),

                    ]),

                Forms\Components\Section::make('Footer')
    ->schema([

        Forms\Components\Textarea::make('footer_text'),

        Forms\Components\TextInput::make('home_heading'),

        Forms\Components\TextInput::make('services_heading'),

        Forms\Components\TextInput::make('programs_heading'),

        Forms\Components\TextInput::make('transformations_heading'),

        Forms\Components\TextInput::make('about_cta_text'),

        Forms\Components\TextInput::make('contact_cta_text'),

        Forms\Components\TextInput::make('view_programs_text'),

        Forms\Components\TextInput::make('view_transformations_text'),

        Forms\Components\TextInput::make('home_label'),

        Forms\Components\TextInput::make('about_label'),

        Forms\Components\TextInput::make('contact_label'),

        Forms\Components\TextInput::make('plan_label'),

        Forms\Components\TextInput::make('transformation_label'),

        Forms\Components\TextInput::make('login_label'),

        Forms\Components\TextInput::make('register_label'),

        Forms\Components\TextInput::make('admin_panel_label'),

        Forms\Components\TextInput::make('my_plan_label'),

        Forms\Components\TextInput::make('my_orders_label'),

        Forms\Components\TextInput::make('logout_label'),

        Forms\Components\TextInput::make('contact_title')
            ->label('Contact Page Label'),

        Forms\Components\TextInput::make('contact_heading')
            ->label('Contact Page Heading'),

        Forms\Components\Textarea::make('contact_description')
            ->label('Contact Page Description')
            ->rows(3),

        Forms\Components\TextInput::make('google_site_verification')
            ->label('Google Site Verification'),

        Forms\Components\TextInput::make('google_analytics_id')
            ->label('Google Analytics ID'),





Forms\Components\TextInput::make('hero_card_title'),

Forms\Components\Textarea::make('hero_card_text'),

Forms\Components\Textarea::make('programs_description'),

Forms\Components\Textarea::make('services_description'),

Forms\Components\TextInput::make('testimonials_title'),

Forms\Components\TextInput::make('testimonials_heading'),

Forms\Components\Textarea::make('testimonials_description'),

Forms\Components\TextInput::make('verified_client_label'),

Forms\Components\Textarea::make('contact_map_text'),

Forms\Components\TextInput::make('bmi_label'),

Forms\Components\TextInput::make('bmi_heading'),

Forms\Components\Textarea::make('bmi_description'),



Forms\Components\TextInput::make('footer_links_heading'),

Forms\Components\TextInput::make('footer_services_heading'),

Forms\Components\TextInput::make('services_page_label'),

Forms\Components\Textarea::make('services_page_description'),

Forms\Components\TextInput::make('programs_page_label'),

Forms\Components\Textarea::make('programs_page_description'),

Forms\Components\TextInput::make('transformations_page_label'),

Forms\Components\TextInput::make('transformations_page_title'),

Forms\Components\Textarea::make('transformations_page_description'),



    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('site_name'),

                Tables\Columns\TextColumn::make('phone'),

                Tables\Columns\TextColumn::make('email'),

            ])
            ->actions([

                Tables\Actions\EditAction::make(),

            ]);
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