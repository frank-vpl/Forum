<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UserBlockController
{
    public function block(int $id): RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }
        if ($id === Auth::id()) {
            return redirect()->route('user.show', ['id' => $id]);
        }
        $target = User::findOrFail($id);
        $me = Auth::user();
        if (! $me->hasBlockedId($target->id)) {
            $me->blocks()->attach($target->id);
        }
        $me->follows()->detach($target->id);
        $target->follows()->detach($me->id);
        Notification::whereIn('type', ['user_follow', 'user_follow_back'])
            ->whereIn('user_id', [$me->id, $target->id])
            ->whereIn('actor_id', [$me->id, $target->id])
            ->delete();

        return redirect()->route('user.show', ['id' => $id]);
    }

    public function unblock(int $id): RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }
        if ($id === Auth::id()) {
            return redirect()->route('user.show', ['id' => $id]);
        }
        $target = User::findOrFail($id);
        $me = Auth::user();
        $me->blocks()->detach($target->id);

        return redirect()->route('user.show', ['id' => $id]);
    }
}
