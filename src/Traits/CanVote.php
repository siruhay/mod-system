<?php

namespace Module\System\Traits;

use Module\System\Models\SystemRating;
use Module\System\Supports\LaravelRating;
use Module\System\Supports\LaravelRatingFacade;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait CanVote
{
    /**
     * votes function
     *
     * @return MorphMany
     */
    public function votes(): MorphMany
    {
        return $this->morphMany(SystemRating::class, 'model')->where('type', LaravelRating::TYPE_VOTE);
    }

    /**
     * upVote function
     *
     * @param [type] $model
     * @return void
     */
    public function upVote($model)
    {
        return LaravelRatingFacade::rate($this, $model, 1, LaravelRating::TYPE_VOTE);
    }

    /**
     * downVote function
     *
     * @param [type] $model
     * @return void
     */
    public function downVote($model)
    {
        return LaravelRatingFacade::rate($this, $model, 0, LaravelRating::TYPE_VOTE);
    }

    /**
     * isVoted function
     *
     * @param [type] $model
     * @return boolean
     */
    public function isVoted($model)
    {
        return LaravelRatingFacade::isRated($this, $model, LaravelRating::TYPE_VOTE);
    }

    /**
     * getVotingValue function
     *
     * @param [type] $model
     * @return void
     */
    public function getVotingValue($model)
    {
        return LaravelRatingFacade::getRatingValue($this, $model, LaravelRating::TYPE_VOTE);
    }

    /**
     * upVoted function
     *
     * @return void
     */
    public function upVoted()
    {
        $upVoted = $this->votes()->where('value', 1)->get();

        return LaravelRatingFacade::resolveRatedItems($upVoted);
    }

    /**
     * downVoted function
     *
     * @return void
     */
    public function downVoted()
    {
        $downVoted = $this->votes()->where('value', 0)->get();

        return LaravelRatingFacade::resolveRatedItems($downVoted);
    }

    /**
     * voted function
     *
     * @return void
     */
    public function voted()
    {
        return LaravelRatingFacade::resolveRatedItems($this->votes);
    }
}
