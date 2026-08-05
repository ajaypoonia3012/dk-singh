<?php

namespace App\Services;

use App\Models\User;

class AccessManager
{
    /**
     * Membership hierarchy
     */
    protected static array $levels = [

        'public' => 0,
        'basic'  => 1,
        'pro'    => 2,
        'elite'  => 3,

    ];

    /**
     * Get user's highest active membership level.
     */
    public static function userLevel(?User $user): string
    {
        if (! $user) {
            return 'public';
        }

        $membership = $user->memberships()
            ->where('status', true)
            ->where('expires_at', '>=', now())
            ->latest('expires_at')
            ->first();

        if (! $membership || ! $membership->plan) {
            return 'public';
        }

        return strtolower(
    $membership->plan->access_type ?? 'public'
);
    }

    /**
     * Generic access checker.
     */
    public static function canAccess(?User $user, string $requiredAccess): bool
    {
        $userLevel = self::userLevel($user);

        return self::$levels[$userLevel] >= self::$levels[strtolower($requiredAccess)];
    }

    /**
     * Workout helper.
     */
    public static function canAccessWorkout(?User $user, $workout): bool
    {
        return self::canAccess(
            $user,
            $workout->required_access ?? 'public'
        );
    }

    /**
     * Diet helper.
     */
    public static function canAccessDiet(?User $user, $diet): bool
    {
        return self::canAccess(
            $user,
            $diet->required_access ?? 'public'
        );
    }
}