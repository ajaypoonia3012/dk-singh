<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transformation extends Model
{
    protected $fillable = [
        'name',
        'before_image',
        'after_image',
        'duration',
        'weight_loss',
        'story',
        'featured',
        'status',
        'image',
        'review',
        'result',
        'description',
        'goal',
    ];
}