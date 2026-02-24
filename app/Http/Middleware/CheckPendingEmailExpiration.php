<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPendingEmailExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (
            $user
            && $user->pending_email
            && $user->pending_email_requested_at
            && $user->pending_email_requested_at->addMinutes(60)->isPast()
        ) {
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'actor_id' => $user->id,
                'type' => 'email_change_expired',
                'post_id' => null,
                'comment_id' => null,
            ]);
            $user->pending_email = null;
            $user->pending_email_token = null;
            $user->pending_email_requested_at = null;
            $user->save();
        }

        return $next($request);
    }
}
