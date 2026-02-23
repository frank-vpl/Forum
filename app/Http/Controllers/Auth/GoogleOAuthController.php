<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Fortify\Features;

class GoogleOAuthController
{
    public function redirect(Request $request): RedirectResponse
    {
        $clientId = (string) config('services.google.client_id');
        $redirectUri = (string) config('services.google.redirect');

        if ($redirect = $request->query('redirect')) {
            $request->session()->put('oauth.redirect', $redirect);
        }

        $state = Str::random(40);
        $request->session()->put('oauth.state', $state);

        $params = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'access_type' => 'offline',
            'prompt' => 'consent',
            'state' => $state,
        ]);

        return redirect()->away('https://accounts.google.com/o/oauth2/v2/auth?'.$params);
    }

    public function callback(Request $request): RedirectResponse
    {
        $state = (string) $request->query('state');
        $expectedState = (string) $request->session()->pull('oauth.state', '');
        if ($expectedState === '' || ! hash_equals($expectedState, $state)) {
            return redirect()->route('login')->withErrors(['email' => 'Invalid OAuth state. Please try again.']);
        }

        $code = (string) $request->query('code', '');
        if ($code === '') {
            return redirect()->route('login')->withErrors(['email' => 'Authorization failed. Please try again.']);
        }

        $clientId = (string) config('services.google.client_id');
        $clientSecret = (string) config('services.google.client_secret');
        $redirectUri = (string) config('services.google.redirect');

        $tokenRes = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'code' => $code,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => $redirectUri,
            'grant_type' => 'authorization_code',
        ]);

        if (! $tokenRes->ok()) {
            return redirect()->route('login')->withErrors(['email' => 'Unable to complete sign in (token).']);
        }

        $accessToken = (string) $tokenRes->json('access_token', '');
        if ($accessToken === '') {
            return redirect()->route('login')->withErrors(['email' => 'Unable to complete sign in (access token).']);
        }

        $userInfoRes = Http::withToken($accessToken)
            ->get('https://www.googleapis.com/oauth2/v3/userinfo');

        if (! $userInfoRes->ok()) {
            return redirect()->route('login')->withErrors(['email' => 'Unable to complete sign in (userinfo).']);
        }

        $email = strtolower(trim((string) $userInfoRes->json('email', '')));
        $name = trim((string) $userInfoRes->json('name', ''));

        if ($email === '') {
            return redirect()->route('login')->withErrors(['email' => 'Google account has no email.']);
        }

        $user = User::where('email', $email)->first();
        if (! $user) {
            $safeName = $name !== '' ? $name : (explode('@', $email)[0] ?? 'User');
            $safeName = mb_substr($safeName, 0, 60);
            try {
                \Illuminate\Support\Facades\DB::transaction(function () use (&$user, $email, $safeName): void {
                    $user = User::create([
                        'name' => $safeName,
                        'email' => $email,
                        'password' => Str::password(32),
                        'status' => 'user',
                        'post_filter' => 'news',
                    ]);
                    $user->forceFill([
                        'email_verified_at' => now(),
                        'remember_token' => Str::random(60),
                    ])->save();
                });
            } catch (\Illuminate\Database\QueryException $e) {
                $user = User::where('email', $email)->first();
                if (! $user) {
                    return redirect()->route('login')
                        ->withErrors(['email' => 'Could not create account via Google (database).']);
                }
            } catch (\Throwable $e) {
                return redirect()->route('login')
                    ->withErrors(['email' => 'Could not create account via Google.']);
            }
        }

        if ($user->isBanned()) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account has been banned.']);
        }

        if (Features::canManageTwoFactorAuthentication() && ! empty($user->two_factor_secret)) {
            $request->session()->put('login.id', $user->getKey());
            $request->session()->put('auth.login_via_oauth', true);
            $request->session()->put('login.remember', true);

            return redirect()->route('two-factor.login');
        }

        Auth::login($user, true);

        $intended = (string) $request->session()->pull('oauth.redirect', '');
        if ($intended !== '') {
            return redirect('/'.$intended);
        }

        return redirect()->intended(route('dashboard'));
    }
}
