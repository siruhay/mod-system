<?php

namespace Module\System\Requests;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Exceptions\HttpResponseException;
use Laravel\Fortify\Contracts\FailedTwoFactorLoginResponse;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;

class TwoFactorResetRequest extends FormRequest
{
    /**
     * The user attempting the two factor challenge.
     *
     * @var mixed
     */
    protected $challengedUser;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'string',
            'password' => ['confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'code' => 'nullable|string',
            'recovery_code' => 'nullable|string',
        ];
    }

    /**
     * updatePassword function
     *
     * @return void
     */
    public function updatePassword()
    {
        $model = app(StatefulGuard::class)->getProvider(null)->getModel();

        if (! $user = $model::firstWhere('email', $this->email)) {
            throw new HttpResponseException(
                app(FailedTwoFactorLoginResponse::class)->toResponse($this)
            );
        }

        return $user->forceFill([
            'password' => Hash::make($this->password)
        ])->save();
    }
    
    /**
     * Determine if the request has a valid two factor code.
     *
     * @return bool
     */
    public function hasValidCode()
    {
        return $this->code && tap(app(TwoFactorAuthenticationProvider::class)->verify(
            decrypt($this->challengedUser()->two_factor_secret),
            $this->code
        ), function ($result) {
            if ($result) {
                $this->session()->forget('login.id');
            }
        });
    }

    /**
     * Get the valid recovery code if one exists on the request.
     *
     * @return string|null
     */
    public function validRecoveryCode()
    {
        if (! $this->recovery_code) {
            return;
        }

        return tap(collect($this->challengedUser()->recoveryCodes())->first(function ($code) {
            return hash_equals($code, $this->recovery_code) ? $code : null;
        }), function ($code) {
            if ($code) {
                $this->session()->forget('login.id');
            }
        });
    }

    /**
     * Get the user that is attempting the two factor challenge.
     *
     * @return mixed
     */
    public function challengedUser()
    {
        if ($this->challengedUser) {
            return $this->challengedUser;
        }

        $model = app(StatefulGuard::class)->getProvider(null)->getModel();

        if (! $user = $model::firstWhere('email', $this->email)) {
            throw new HttpResponseException(
                app(FailedTwoFactorLoginResponse::class)->toResponse($this)
            );
        }

        return $this->challengedUser = $user;
    }
}
