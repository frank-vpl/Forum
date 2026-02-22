<?php

namespace App\Http\Responses;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\FailedLoginResponse as FailedLoginResponseContract;

class FailedLoginResponse implements FailedLoginResponseContract
{
    public function toResponse($request): RedirectResponse
    {
        $message = trans('auth.failed');

        $email = (string) $request->input('email');
        if ($email !== '') {
            $user = User::where('email', $email)->first();
            if ($user && $user->status === 'banned') {
                $message = 'Your account has been banned.';
            }
        }

        throw ValidationException::withMessages([
            'email' => [$message],
        ]);
    }
}

