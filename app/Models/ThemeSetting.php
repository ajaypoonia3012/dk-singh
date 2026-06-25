<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeSetting extends Model
{
    protected $fillable = [

        'primary_color',
        'secondary_color',
        'accent_color',

        'show_programs',
        'show_services',
        'show_products',
        'show_blogs',
        'show_transformations',
        'show_plans',

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

        'show_programs' => 'boolean',
        'show_services' => 'boolean',
        'show_products' => 'boolean',
        'show_blogs' => 'boolean',
        'show_transformations' => 'boolean',
        'show_plans' => 'boolean',

        'announcement_enabled' => 'boolean',
        'popup_enabled' => 'boolean',

    ];
}