<?php

namespace App\Http\Middleware;

use App\Models\Membership;
use Closure;
use Illuminate\Http\Request;

class ActiveMembership
{
    public function handle(Request $request, Closure $next)
    {
        $membership = Membership::where('user_id', auth()->id())
            ->where('status', true)
            ->where('expires_at', '>=', now())
            ->first();

        if (! $membership) {

            return redirect('/plans')
                ->with('error', 'Active membership required.');

        }

        return $next($request);
    }
}
