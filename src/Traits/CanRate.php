<?php

namespace Module\System\Traits;

use Module\System\Models\SystemRating;
use Module\System\Supports\LaravelRating;
use Module\System\Supports\LaravelRatingFacade;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait CanRate
{
    /**
     * ratings function
     *
     * @return MorphMany
     */
    public function ratings(): MorphMany
    {
        return $this->morphMany(SystemRating::class, 'model')->where('type', LaravelRating::TYPE_RATE);
    }

    /**
     * rate function
     *
     * @param [type] $model
     * @param [type] $value
     * @return void
     */
    public function rate($model, $value)
    {
        if ($value === null || $value === false || $value === -1) {
            return $this->unRate($model);
        }

        return LaravelRatingFacade::rate($this, $model, $value, LaravelRating::TYPE_RATE);
    }

    /**
     * unRate function
     *
     * @param [type] $model
     * @return void
     */
    public function unRate($model)
    {
        return LaravelRatingFacade::unRate($this, $model, LaravelRating::TYPE_RATE);
    }

    /**
     * getRatingValue function
     *
     * @param [type] $model
     * @return void
     */
    public function getRatingValue($model)
    {
        return LaravelRatingFacade::getRatingValue($this, $model, LaravelRating::TYPE_RATE);
    }

    /**
     * isRated function
     *
     * @param [type] $model
     * @return boolean
     */
    public function isRated($model)
    {
        return LaravelRatingFacade::isRated($this, $model, LaravelRating::TYPE_RATE);
    }

    /**
     * rated function
     *
     * @return void
     */
    public function rated()
    {
        return LaravelRatingFacade::resolveRatedItems($this->ratings);
    }
}
