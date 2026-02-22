<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PersistAuthRedirect
{
    protected array $authRoutePrefixes = [
        'login',
        'register',
        'password.',
        'two-factor.',
        'verification.',
        'logout',
    ];

    protected array $allowedPrefixes = [
        '/dashboard',
        '/forum',
        '/users',
        '/user',
        '/notifications',
        '/my',
        '/new',
        '/premium',
        '/settings',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()?->getName() ?? '';
        $isAuthRoute = $this->isAuthRouteName($routeName);

        if ($isAuthRoute) {
            $redirectParam = $request->query('redirect');
            if ($redirectParam) {
                $decoded = urldecode($redirectParam);
                $value = '/'.ltrim($decoded, '/');
                $value = $this->isAllowedRedirectPath($value) ? $value : route('dashboard', absolute: false);
                $request->session()->put('redirect_after_auth', $value);
                $request->session()->put('url.intended', url($value));
            } else {
                if ($this->isLoginOrRegister($routeName)) {
                    $referer = $request->headers->get('referer');
                    $target = $this->extractPathFromReferer($referer, $request);
                    if ($target && ! $this->refererIsAuth($target) && ! $this->isHomePath($target)) {
                        $onlyPath = parse_url($target, PHP_URL_PATH) ?: '/';
                        $value = $this->isAllowedRedirectPath($onlyPath)
                            ? ltrim($target, '/')
                            : ltrim(route('dashboard', absolute: false), '/');
                        $encoded = rawurlencode($value);
                        return redirect()->to($request->url().'?redirect='.$encoded);
                    }
                }
            }

            return $next($request);
        }

        if (Auth::check()) {
            $target = $request->session()->get('redirect_after_auth');
            if ($target) {
                $currentPath = '/'.ltrim($request->path(), '/');
                if ($currentPath === $target) {
                    $request->session()->forget('redirect_after_auth');
                } else {
                    return redirect()->to($target);
                }
            }
        }

        return $next($request);
    }

    protected function isAuthRouteName(string $name): bool
    {
        foreach ($this->authRoutePrefixes as $prefix) {
            if ($prefix === 'logout' && $name === 'logout') {
                return true;
            }
            if (str_starts_with($name, $prefix)) {
                return true;
            }
        }
        return false;
    }

    protected function isHomePath(string $path): bool
    {
        $onlyPath = parse_url($path, PHP_URL_PATH) ?: '/';
        return $onlyPath === '/';
    }

    protected function isAllowedRedirectPath(string $path): bool
    {
        $onlyPath = parse_url($path, PHP_URL_PATH) ?: '/';
        foreach ($this->allowedPrefixes as $prefix) {
            if (str_starts_with($onlyPath, $prefix)) {
                return true;
            }
        }
        return false;
    }



    protected function isLoginOrRegister(string $name): bool
    {
        return $name === 'login' || $name === 'register';
    }

    protected function extractPathFromReferer(?string $referer, Request $request): ?string
    {
        if (! $referer) {
            return null;
        }
        $refHost = parse_url($referer, PHP_URL_HOST);
        if ($refHost && $refHost !== $request->getHost()) {
            return null;
        }
        $path = parse_url($referer, PHP_URL_PATH) ?: '/';
        $query = parse_url($referer, PHP_URL_QUERY);
        $target = $path;
        if ($query) {
            $target .= '?'.$query;
        }
        return $target;
    }

    protected function refererIsAuth(string $path): bool
    {
        return preg_match('#^/(login|register|password|two-factor|email)#', $path) === 1;
    }
}
