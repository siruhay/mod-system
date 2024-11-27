<?php

namespace Module\System\Supports;

use Illuminate\Support\Facades\Facade;

class LaravelRatingFacade extends Facade
{
    /**
     * getFacadeAccessor function
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return 'laravelRating';
    }
}
