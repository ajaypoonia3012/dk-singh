<?php

namespace App\Http\Middleware;

use App\Models\Membership;
use Closure;
use Illuminate\Http\Request;

class MembershipMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        /*
        |--------------------------------------------------------------------------
        | USER LOGIN CHECK
        |--------------------------------------------------------------------------
        */

        if (! auth()->check()) {

            return redirect('/login');

        }

        /*
        |--------------------------------------------------------------------------
        | ACTIVE MEMBERSHIP CHECK
        |--------------------------------------------------------------------------
        */

        $membership = Membership::where('user_id', auth()->id())
            ->where('status', true)
            ->where('expires_at', '>=', now())
            ->latest()
            ->first();

        if (! $membership) {

            return redirect('/plans')
                ->with('error', 'You need an active membership plan.');

        }

        return $next($request);
    }
}
