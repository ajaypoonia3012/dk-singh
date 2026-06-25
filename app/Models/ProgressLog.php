<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressLog extends Model
{
    protected $fillable = [

        'user_id',

        'weight',
        'bmi',
        'body_fat',

        'chest',
        'waist',
        'arms',
        'thighs',

        'front_photo',
        'side_photo',
        'back_photo',

        'notes',

    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}