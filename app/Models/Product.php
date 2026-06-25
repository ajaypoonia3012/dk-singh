<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
   protected $fillable = [

    'name',
    'slug',
 'sku',
    'description',
    'image',
    'price',
'weight',
    'featured',
    'status',

];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($product) {

            if (empty($product->slug)) {

                $product->slug = Str::slug($product->name);

            }

        });
    }
}
