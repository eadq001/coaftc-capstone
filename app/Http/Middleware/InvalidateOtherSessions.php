<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class InvalidateOtherSessions
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && config('session.driver') === 'database') {
            $sessionId = $request->session()->getId();
            $userId = Auth::id();

            $valid = DB::table('sessions')
                ->where('id', $sessionId)
                ->where('user_id', $userId)
                ->exists();

            if (! $valid) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect('/login')->with('status', 'Your session was ended because your account was logged in elsewhere.');
            }
        }

        return $next($request);
    }
}
