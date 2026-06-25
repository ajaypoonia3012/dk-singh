<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Notification;

class CoachNote extends Model
{
    protected $fillable = [

        'user_id',
        'note',
        'is_visible',

    ];

    protected static function booted()
    {
        static::created(function ($coachNote) {

            Notification::create([

                'user_id' => $coachNote->user_id,

                'title' => 'New Coach Feedback',

                'message' =>
                    'Your coach has provided new feedback. Please review it from your dashboard.',

                'is_read' => false,

            ]);

        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}