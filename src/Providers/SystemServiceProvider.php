<?php

namespace Module\System\Providers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Module\System\Actions\Fortify\CreateNewUser;
use Module\System\Models\SystemPersonalAccessToken;
use Module\System\Actions\Fortify\ResetUserPassword;
use Module\System\Actions\Fortify\UpdateUserPassword;
use Module\System\Actions\Fortify\UpdateUserProfileInformation;

class SystemServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        Sanctum::usePersonalAccessTokenModel(SystemPersonalAccessToken::class);

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Gate::guessPolicyNamesUsing(function ($modelClass) {
            return str($modelClass)->before('\\Models\\')->toString() . '\\Policies\\' . str($modelClass)->after('\\Models\\')->toString() . 'Policy';
        });

        $this->loadViewsFrom(
            __DIR__ . '/../../resources/views',
            'system'
        );

        $this->commands($this->discoverCommands());
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * The discoverCommands function
     *
     * @return array
     */
    protected function discoverCommands(): array
    {
        $commandPath = __DIR__ . '/../Commands';
        $commands = [];

        if (!$this->app->files->exists($commandPath)) {
            return $commands;
        }

        foreach ($this->app->files->allFiles($commandPath) as $command) {
            $className = 'Module\\System\\Commands\\' . str($command->getBasename())->before('.php')->toString();

            if (class_exists($className)) {
                array_push($commands, $className);
            }
        }

        return $commands;
    }
}
