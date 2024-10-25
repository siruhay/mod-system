<?php

namespace Module\System\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Fortify\Contracts\TwoFactorDisabledResponse;
use Laravel\Fortify\Contracts\TwoFactorConfirmedResponse;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Module\System\Requests\UpdatePasswordRequest;

class TwoFactorAuthenticationController extends Controller
{
    /**
     * Enable two factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Fortify\Actions\EnableTwoFactorAuthentication  $enable
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request, EnableTwoFactorAuthentication $enable)
    {
        $enable($request->user(), $request->boolean('force', false));

        if (is_null($request->user()->two_factor_secret)) {
            return [];
        }

        return response()->json([
            'recovery' => $request->user()->recoveryCodes(),
            'svg' => $request->user()->twoFactorQrCodeSvg(),
            'url' => $request->user()->twoFactorQrCodeUrl(),
        ]);
    }

    /**
     * Enable two factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication  $confirm
     * @return \Laravel\Fortify\Contracts\TwoFactorConfirmedResponse
     */
    public function confirm(Request $request, ConfirmTwoFactorAuthentication $confirm)
    {
        $confirm($request->user(), $request->input('code'));

        return app(TwoFactorConfirmedResponse::class);
    }

    /**
     * updatePassword function
     *
     * @param UpdatePasswordRequest $request
     * @return void
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        return $request->user()->updatePassword($request, $request->user());
    }

    /**
     * updateProfile function
     *
     * @param Request $request
     * @return void
     */
    public function updateProfile(Request $request)
    {
        return $request->user()->updateProfile($request, $request->user());
    }

    /**
     * Get the SVG element for the user's two factor authentication QR code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request)
    {
        if (is_null($request->user()->two_factor_secret)) {
            return [];
        }

        return response()->json([
            'svg' => $request->user()->twoFactorQrCodeSvg(),
            'url' => $request->user()->twoFactorQrCodeUrl(),
        ]);
    }

    /**
     * Disable two factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Fortify\Actions\DisableTwoFactorAuthentication  $disable
     * @return \Laravel\Fortify\Contracts\TwoFactorDisabledResponse
     */
    public function destroy(Request $request, DisableTwoFactorAuthentication $disable)
    {
        $disable($request->user());

        return app(TwoFactorDisabledResponse::class);
    }
}
