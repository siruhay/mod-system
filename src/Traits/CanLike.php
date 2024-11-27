<?php

namespace Module\System\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Module\System\Models\SystemRating;
use Module\System\Supports\LaravelRating;
use Module\System\Supports\LaravelRatingFacade;

trait CanLike
{
    /**
     * likes function
     *
     * @return morphMany
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(SystemRating::class, 'model')->where('type', LaravelRating::TYPE_LIKE);
    }

    /**
     * like function
     *
     * @param [type] $model
     * @return void
     */
    public function like($model)
    {
        return LaravelRatingFacade::rate($this, $model, 1, LaravelRating::TYPE_LIKE);
    }

    /**
     * dislike function
     *
     * @param [type] $model
     * @return void
     */
    public function dislike($model)
    {
        return LaravelRatingFacade::rate($this, $model, 0, LaravelRating::TYPE_LIKE);
    }

    /**
     * isLiked function
     *
     * @param [type] $model
     * @return boolean
     */
    public function isLiked($model)
    {
        return LaravelRatingFacade::isRated($this, $model, LaravelRating::TYPE_LIKE);
    }

    /**
     * liked function
     *
     * @return void
     */
    public function liked()
    {
        $liked = $this->likes()->where('value', 1)->get();

        return LaravelRatingFacade::resolveRatedItems($liked);
    }

    /**
     * disliked function
     *
     * @return void
     */
    public function disliked()
    {
        $disliked = $this->likes()->where('value', 0)->get();

        return LaravelRatingFacade::resolveRatedItems($disliked);
    }

    /**
     * likedDisliked function
     *
     * @return void
     */
    public function likedDisliked()
    {
        return LaravelRatingFacade::resolveRatedItems($this->likes);
    }
}
