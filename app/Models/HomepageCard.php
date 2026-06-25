<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageCard extends Model
{
    protected $fillable = [

        'title',
        'subtitle',
        'icon',
        'sort_order',
        'is_active',

    ];
}