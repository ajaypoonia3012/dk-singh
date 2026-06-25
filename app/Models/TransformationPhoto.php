<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransformationPhoto extends Model
{
    protected $fillable = [

        'user_id',
        'front_photo',
        'side_photo',
        'back_photo',
        'notes',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}