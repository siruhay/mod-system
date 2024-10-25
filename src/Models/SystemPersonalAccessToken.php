<?php

namespace Module\System\Models;

use Laravel\Sanctum\PersonalAccessToken;

class SystemPersonalAccessToken extends PersonalAccessToken
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'platform';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_personal_access_tokens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'token',
        'ip_address',
        'abilities',
        'expires_at'
    ];
}
