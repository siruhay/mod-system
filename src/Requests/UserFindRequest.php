<?php

namespace Module\System\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserFindRequest extends FormRequest
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
        ];
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
            throw new HttpResponseException(response()->json([
                'message' => 'Data pengguna tidak ditemukan pada system.'
            ], 422));
        }

        if (is_null($user->two_factor_secret) || is_null($user->two_factor_confirmed_at)) {
            throw new HttpResponseException(response()->json([
                'message' => 'Data pengguna tidak ditemukan pada system.'
            ], 422));
        }

        return $this->challengedUser = $user;
    }
}