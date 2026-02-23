<?php

namespace App\Providers;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\FailedLoginResponse;
use Laravel\Fortify\Fortify;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FailedLoginResponse::class, \App\Http\Responses\FailedLoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureAuthGuards();
        $this->registerBladeDirectives();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }

    /**
     * Configure authentication behavior such as banning policy.
     */
    protected function configureAuthGuards(): void
    {
        if (class_exists(Fortify::class)) {
            Fortify::authenticateUsing(function (Request $request): ?User {
                $user = User::where('email', $request->input('email'))->first();
                if (! $user) {
                    return null;
                }
                if (! Hash::check($request->input('password'), $user->password)) {
                    return null;
                }
                if ($user->status === 'banned') {
                    throw ValidationException::withMessages([
                        'email' => ['Your account has been banned.'],
                    ]);
                }

                return $user;
            });
        }
    }

    protected function registerBladeDirectives(): void
    {
        Blade::directive('format_count', function ($expression): string {
            return "<?php \$__n=(int)($expression); if (\$__n<1000){echo \$__n;} elseif (\$__n<1000000){\$__v=round(\$__n/1000,1);\$__s=rtrim(rtrim(number_format(\$__v,1,'.',''),'0'),'.');echo \$__s.'k';} elseif (\$__n<1000000000){\$__v=round(\$__n/1000000,1);\$__s=rtrim(rtrim(number_format(\$__v,1,'.',''),'0'),'.');echo \$__s.'M';} elseif (\$__n>=1000000000000){echo '+999B';} else { \$__v=round(\$__n/1000000000,1);\$__s=rtrim(rtrim(number_format(\$__v,1,'.',''),'0'),'.');echo \$__s.'B';} ?>";
        });
    }
}
