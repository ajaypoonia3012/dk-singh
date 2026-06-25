<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Notification;

class ActionPlan extends Model
{
    protected $fillable = [

        'user_id',
        'title',
        'description',
        'due_date',
        'is_completed',
        'completed_at',

    ];

    protected $casts = [

        'due_date' => 'date',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',

    ];

    protected static function booted()
    {
        static::created(function ($actionPlan) {

            Notification::create([

                'user_id' => $actionPlan->user_id,

                'title' => $actionPlan->title,

                'message' => $actionPlan->description,

                'is_read' => false,

            ]);

        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}