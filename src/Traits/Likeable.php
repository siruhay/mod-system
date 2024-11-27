<?php

namespace Module\System\Traits;

use Module\System\Models\SystemRating;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Likeable
{
    /**
     * likes function
     *
     * @return MorphMany
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(SystemRating::class, 'rateable');
    }

    /**
     * likesDislikesCount function
     *
     * @return integer
     */
    public function likesDislikesCount(): int
    {
        return $this->likes()->count();
    }

    /**
     * likesCount function
     *
     * @return integer
     */
    public function likesCount(): int
    {
        return $this->likes()->where('value', 1)->count();
    }

    /**
     * dislikesCount function
     *
     * @return integer
     */
    public function dislikesCount(): int
    {
        return $this->likes()->where('value', 0)->count();
    }
}
