<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeSetting extends Model
{
    protected $fillable = [

        'primary_color',
        'secondary_color',
        'accent_color',

       'show_hero',

'show_programs',
'show_services',
'show_products',
'show_blogs',
'show_transformations',
'show_plans',

'show_about',
'show_bmi',
'show_homepage_cards',
'show_testimonials',
'show_contact',

        'announcement_enabled',
        'announcement_text',
        'announcement_link',

        'popup_enabled',
        'popup_title',
        'popup_description',
        'popup_button_text',
        'popup_button_link',
        'popup_image',

        'clients_count',
        'coached_count',
        'programs_count',
        'countries_count',

        'theme_name',

    ];

    protected $casts = [

        'show_hero' => 'boolean',

'show_programs' => 'boolean',
'show_services' => 'boolean',
'show_products' => 'boolean',
'show_blogs' => 'boolean',
'show_transformations' => 'boolean',
'show_plans' => 'boolean',

'show_about' => 'boolean',
'show_bmi' => 'boolean',
'show_homepage_cards' => 'boolean',
'show_testimonials' => 'boolean',
'show_contact' => 'boolean',

        'announcement_enabled' => 'boolean',
        'popup_enabled' => 'boolean',

    ];
}