<?php

namespace Module\System\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Features;
use Illuminate\Routing\Pipeline;
use Illuminate\Support\Facades\DB;
use PHPUnit\Event\Telemetry\System;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Module\System\Models\SystemUser;
use Module\System\Notifications\UserLogged;
use Module\System\Requests\UserFindRequest;
use Illuminate\Contracts\Auth\StatefulGuard;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Http\Requests\LoginRequest;
use Laravel\Fortify\Contracts\LoginViewResponse;
use Laravel\Fortify\Events\RecoveryCodeReplaced;
use Laravel\Fortify\Actions\CanonicalizeUsername;
use Module\System\Requests\TwoFactorResetRequest;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Laravel\Fortify\Http\Requests\TwoFactorLoginRequest;
use Laravel\Fortify\Contracts\FailedTwoFactorLoginResponse;
use Module\System\Http\Resources\AuthenticatedShowResource;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;

class AuthenticatedSessionController extends Controller
{
    /**
     * The guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected $guard;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @return void
     */
    public function __construct(StatefulGuard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * Show the login view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Laravel\Fortify\Contracts\LoginViewResponse
     */
    public function create(Request $request): LoginViewResponse
    {
        return app(LoginViewResponse::class);
    }

    /**
     * Attempt to authenticate a new session.
     *
     * @param  \Laravel\Fortify\Http\Requests\LoginRequest  $request
     * @return mixed
     */
    public function store(LoginRequest $request)
    {
        return $this->loginPipeline($request)->then(function ($request) {
            return app(LoginResponse::class);
        });
    }

    /**
     * update function
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request)
    {
        $request->validate([
            'coords' => 'required',
        ]);

        DB::table(env('SESSION_TABLE'))
            ->where('user_id', $request->user()->getAuthIdentifier())
            ->where('id', $request->session()->getId())
            ->update([
                'geolocation' => json_encode($request->coords)
            ]);

        $request->user()->forceFill([
            'last_geolocation' => json_encode($request->coords)
        ])->save();

        $request->user()->notify(new UserLogged($request->user()));

        return response()->json();
    }

    /**
     * Attempt to authenticate a new session using the two factor authentication code.
     *
     * @param  \Laravel\Fortify\Http\Requests\TwoFactorLoginRequest  $request
     * @return mixed
     */
    public function challenge(TwoFactorLoginRequest $request)
    {
        $user = $request->challengedUser();

        if ($code = $request->validRecoveryCode()) {
            $user->replaceRecoveryCode($code);

            event(new RecoveryCodeReplaced($user, $code));
        } elseif (! $request->hasValidCode()) {
            return app(FailedTwoFactorLoginResponse::class)->toResponse($request);
        }

        $this->guard->login($user, $request->remember());

        $request->session()->regenerate();

        return app(TwoFactorLoginResponse::class);
    }

    /**
     * userfind function
     *
     * @param UserFindRequest $request
     * @return mixed
     */
    public function userfind(UserFindRequest $request)
    {
        $request->challengedUser();

        return app(TwoFactorLoginResponse::class);
    }

    /**
     * resetpass function
     *
     * @param TwoFactorResetRequest $request
     * @return mixed
     */
    public function resetpass(TwoFactorResetRequest $request)
    {
        $user = $request->challengedUser();

        if ($code = $request->validRecoveryCode()) {
            $user->replaceRecoveryCode($code);

            event(new RecoveryCodeReplaced($user, $code));
        } elseif (! $request->hasValidCode()) {
            return app(FailedTwoFactorLoginResponse::class)->toResponse($request);
        }

        $request->updatePassword();

        return app(TwoFactorLoginResponse::class);
    }

    /**
     * The show function
     *
     * @param Request $request
     * @return void
     */
    public function show(Request $request)
    {
        return new AuthenticatedShowResource(
            $request->user()->load(
                'licenses',
                'licenses.ability',
                'licenses.ability.pages',
                'licenses.ability.pages.page',
                'licenses.ability.pages.page.parent',
                'licenses.module',
            )
        );
    }

    /**
     * userModules function
     *
     * @param Request $request
     * @return void
     */
    public function userModules(Request $request)
    {
        return new AuthenticatedShowResource(
            $request->user()->load(
                'licenses',
                'licenses.ability',
                'licenses.ability.pages',
                'licenses.ability.pages.page',
                'licenses.ability.pages.page.parent',
                'licenses.module',
            )
        );
    }

    /**
     * Get the authentication pipeline instance.
     *
     * @param  \Laravel\Fortify\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Pipeline\Pipeline
     */
    protected function loginPipeline(LoginRequest $request)
    {
        if (Fortify::$authenticateThroughCallback) {
            return (new Pipeline(app()))->send($request)->through(array_filter(
                call_user_func(Fortify::$authenticateThroughCallback, $request)
            ));
        }

        if (is_array(config('fortify.pipelines.login'))) {
            return (new Pipeline(app()))->send($request)->through(array_filter(
                config('fortify.pipelines.login')
            ));
        }

        return (new Pipeline(app()))->send($request)->through(array_filter([
            config('fortify.limiters.login') ? null : EnsureLoginIsNotThrottled::class,
            config('fortify.lowercase_usernames') ? CanonicalizeUsername::class : null,
            Features::enabled(Features::twoFactorAuthentication()) ? RedirectIfTwoFactorAuthenticatable::class : null,
            AttemptToAuthenticate::class,
            PrepareAuthenticatedSession::class,
        ]));
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Laravel\Fortify\Contracts\LogoutResponse
     */
    public function destroy(Request $request): LogoutResponse
    {
        $this->guard->logout();

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return app(LogoutResponse::class);
    }

    /**
     * impersonateTake function
     *
     * @param Request $request
     * @param string $sourceId
     * @return void
     */
    public function impersonateTake(Request $request, string $sourceId)
    {
        if ($request->user()->hasLicenseAs('system-superadmin') && $source = SystemUser::firstWhere('email', $sourceId)) {
            $request->user()->impersonateTake($source);
        }
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function impersonateLeave(Request $request)
    {
        if ($request->session()->has('impersonate_source_id') && $user = SystemUser::find($request->session()->get('impersonate_origin_id'))) {
            $request->user()->impersonateLeave();

            return new AuthenticatedShowResource(
                $user->load(
                    'licenses',
                    'licenses.ability',
                    'licenses.ability.pages',
                    'licenses.ability.pages.page',
                    'licenses.ability.pages.page.parent',
                    'licenses.module',
                )
            );
        }
    }
}
