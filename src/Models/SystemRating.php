<?php

namespace Module\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SystemRating extends Model
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
    protected $table = 'system_ratings';

    /**
     * The roles variable
     *
     * @var array
     */
    protected $roles = ['system-rating'];

    /**
     * model function
     *
     * @return MorphTo
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * rateable function
     *
     * @return MorphTo
     */
    public function rateable(): MorphTo
    {
        return $this->morphTo();
    }
}
