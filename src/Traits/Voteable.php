<?php

namespace Module\System\Traits;

use Module\System\Models\SystemRating;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Votable
{
    /**
     * votes function
     *
     * @return MorphMany
     */
    public function votes(): MorphMany
    {
        return $this->morphMany(SystemRating::class, 'rateable');
    }

    /**
     * Undocumented function
     *
     * @return integer
     */
    public function totalVotesCount(): int
    {
        return $this->votes()->count();
    }

    /**
     * upVotesCount function
     *
     * @return integer
     */
    public function upVotesCount(): int
    {
        return $this->votes()->where('value', 1)->count();
    }

    /**
     * downVotesCount function
     *
     * @return integer
     */
    public function downVotesCount(): int
    {
        return $this->votes()->where('value', 0)->count();
    }

    /**
     * votesDiff function
     *
     * @return integer
     */
    public function votesDiff(): int
    {
        return $this->upVotesCount() - $this->downVotesCount();
    }
}
