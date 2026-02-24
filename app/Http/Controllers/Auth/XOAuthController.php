<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Fortify\Features;

class XOAuthController
{
    public function redirect(Request $request): RedirectResponse
    {
        $clientId = (string) config('services.x.client_id');
        $redirectUri = (string) config('services.x.redirect');

        if ($redirect = $request->query('redirect')) {
            $request->session()->put('oauth.redirect', $redirect);
        }

        // PKCE: generate verifier + challenge
        $codeVerifier = Str::random(128); // high-entropy random string (43–128 chars)
        $codeChallenge = strtr(rtrim(base64_encode(hash('sha256', $codeVerifier, true)), '='), '+/', '-_');

        $state = Str::random(40);

        $request->session()->put('oauth.state', $state);
        $request->session()->put('oauth.pkce', $codeVerifier);

        $params = http_build_query([
            'response_type' => 'code',
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'scope' => 'users.read tweet.read users.email offline.access', // add more scopes if needed
            'state' => $state,
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => 'S256',
        ]);

        return redirect()->away('https://x.com/i/oauth2/authorize?'.$params);
    }

    public function callback(Request $request): RedirectResponse
    {
        $state = (string) $request->query('state');
        $expectedState = (string) $request->session()->pull('oauth.state', '');

        if ($expectedState === '' || ! hash_equals($expectedState, $state)) {
            return redirect()->route('login')->withErrors(['email' => 'Invalid OAuth state.']);
        }

        $code = (string) $request->query('code', '');
        if ($code === '') {
            return redirect()->route('login')->withErrors(['email' => 'Authorization failed.']);
        }

        $clientId = (string) config('services.x.client_id');
        $clientSecret = (string) config('services.x.client_secret');
        $redirectUri = (string) config('services.x.redirect');

        // Retrieve the code_verifier we saved earlier
        $codeVerifier = (string) $request->session()->pull('oauth.pkce', '');

        if ($codeVerifier === '') {
            return redirect()->route('login')->withErrors(['email' => 'PKCE verifier missing.']);
        }

        $tokenRes = Http::asForm()->withBasicAuth($clientId, $clientSecret)->post('https://api.x.com/2/oauth2/token', [
            'code' => $code,
            'grant_type' => 'authorization_code',
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'code_verifier' => $codeVerifier,
        ]);

        if (! $tokenRes->ok()) {
            return redirect()->route('login')->withErrors(['email' => 'Token request failed: '.$tokenRes->body()]);
        }

        $accessToken = (string) $tokenRes->json('access_token', '');
        if ($accessToken === '') {
            return redirect()->route('login')->withErrors(['email' => 'No access token received.']);
        }

        // Get user info — IMPORTANT: request confirmed_email
        $userInfoRes = Http::withToken($accessToken)
            ->get('https://api.x.com/2/users/me', [
                'user.fields' => 'confirmed_email,name,username,profile_image_url',
            ]);

        if (! $userInfoRes->ok()) {
            return redirect()->route('login')->withErrors(['email' => 'User info request failed.']);
        }

        $data = $userInfoRes->json('data', []);

        $email = strtolower(trim((string) ($data['confirmed_email'] ?? '')));
        $name = trim((string) ($data['name'] ?? ''));
        $username = trim((string) ($data['username'] ?? '')); // X handle — useful fallback / display

        if ($email === '') {
            return redirect()->route('login')->withErrors(['email' => 'X account has no confirmed email or email access not granted.']);
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            $safeName = $name !== '' ? $name : ($username ?: (explode('@', $email)[0] ?? 'User'));
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
                        ->withErrors(['email' => 'Could not create account via X (database conflict).']);
                }
            } catch (\Throwable $e) {
                return redirect()->route('login')
                    ->withErrors(['email' => 'Could not create account via X.']);
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
