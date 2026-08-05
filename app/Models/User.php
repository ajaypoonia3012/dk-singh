<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }
    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'name',
        'email',
        'password',

        'phone',
        'whatsapp_number',

        'gender',
        'age',
        'height',
        'weight',
        'bmi',

        'goal',
        'activity_level',

        'diet_preference',
        'allergies',
        'medical_conditions',

        'city',
        'country',

        'profile_photo',
        'bio',

        'is_premium',
        'is_coach',
        'is_admin',
        'account_type',
        'profile_completed',

    ];

    /*
    |--------------------------------------------------------------------------
    | HIDDEN
    |--------------------------------------------------------------------------
    */

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_premium' => 'boolean',
            'is_coach' => 'boolean',
            'is_admin' => 'boolean',
            'profile_completed' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | MEMBERSHIPS
    |--------------------------------------------------------------------------
    */

    public function activeMembership()
    {
        return $this->hasOne(Membership::class)
            ->where('status', true)
            ->where('expires_at', '>=', now())
            ->latestOfMany();
    }

    public function isAdmin(): bool
    {
        return $this->account_type === 'admin' || $this->is_admin === true;
    }

    public function isMember()
    {
        return $this->activeMembership()->exists();
    }

    public function isCustomer()
    {
        return ! $this->isAdmin()
            && ! $this->isMember();
    }

    protected static function booted(): void
    {
        static::saving(function (User $user): void {
            if ($user->isDirty('account_type')) {
                $user->is_admin = $user->account_type === 'admin';

                return;
            }

            if ($user->isDirty('is_admin')) {
                $user->account_type = $user->is_admin
                    ? 'admin'
                    : ($user->account_type === 'admin' ? 'customer' : $user->account_type);
            }
        });
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESS HELPERS
    |--------------------------------------------------------------------------
    */

    public function hasBasicAccess(): bool
    {
        return $this->activeMembership &&
            in_array(
                $this->activeMembership->plan->access_type,
                ['basic', 'pro', 'elite']
            );
    }

    public function hasProAccess(): bool
    {
        return $this->activeMembership &&
            in_array(
                $this->activeMembership->plan->access_type,
                ['pro', 'elite']
            );
    }

    public function hasEliteAccess(): bool
    {
        return $this->activeMembership &&
            $this->activeMembership->plan->access_type === 'elite';
    }

    /*
    |--------------------------------------------------------------------------
    | ORDERS
    |--------------------------------------------------------------------------
    */

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /*
    |--------------------------------------------------------------------------
    | PROGRESS LOGS
    |--------------------------------------------------------------------------
    */

    public function progressLogs()
    {
        return $this->hasMany(ProgressLog::class);
    }

    public function weeklyCheckIns()
    {
        return $this->hasMany(
            WeeklyCheckIn::class
        );
    }

    public function coachNotes()
    {
        return $this->hasMany(
            CoachNote::class
        );
    }

    public function notificationsList()
    {
        return $this->hasMany(
            Notification::class
        );
    }
}
