<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfileCompletedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    if (!auth()->check()) {

        return redirect('/login');

    }

    if (!auth()->user()->profile_completed) {

        return redirect('/member/profile')
            ->with(
                'error',
                'Please complete your profile first.'
            );

    }

    return $next($request);
}
}
