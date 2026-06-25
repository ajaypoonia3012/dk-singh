<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [

        'name',
        'location',
        'review',
        'image',
        'rating',
        'featured',
        'status'

    ];
}