<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EmailChangeController
{
    public function verify(Request $request): Response
    {
        if (! $request->hasValidSignature()) {
            return redirect()->route('dashboard')->with('status', 'invalid-email-change-link')->with('status_color', 'red');
        }

        $id = (int) $request->query('id', 0);
        $token = (string) $request->query('token', '');

        /** @var \App\Models\User|null $user */
        $user = User::find($id);
        if (! $user || $user->pending_email === null || $user->pending_email_token === null) {
            return redirect()->route('dashboard')->with('status', 'invalid-email-change-state')->with('status_color', 'red');
        }

        if (! hash_equals($user->pending_email_token, $token)) {
            return redirect()->route('dashboard')->with('status', 'invalid-email-change-token')->with('status_color', 'red');
        }

        if (! $user->pending_email_requested_at || $user->pending_email_requested_at->addMinutes(60)->isPast()) {
            $user->pending_email = null;
            $user->pending_email_token = null;
            $user->pending_email_requested_at = null;
            $user->save();
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'actor_id' => $user->id,
                'type' => 'email_change_expired',
                'post_id' => null,
                'comment_id' => null,
            ]);

            return response()->view('errors.403', ['status' => 'email-change-expired'], 403);
        }

        $user->email = $user->pending_email;
        $user->email_verified_at = now();
        $user->pending_email = null;
        $user->pending_email_token = null;
        $user->pending_email_requested_at = null;
        $user->save();

        if (! Auth::check()) {
            Auth::login($user, true);
        }

        \App\Models\Notification::create([
            'user_id' => $user->id,
            'actor_id' => $user->id,
            'type' => 'email_changed',
            'post_id' => null,
            'comment_id' => null,
        ]);

        return redirect()->route('dashboard')->with('status', 'email-changed')->with('status_color', 'green');
    }
}
