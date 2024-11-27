<?php

namespace Module\System\Traits;

use Module\System\Models\SystemRating;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Rateable
{
    /**
     * ratings function
     *
     * @return MorphMany
     */
    public function ratings(): MorphMany
    {
        return $this->morphMany(SystemRating::class, 'rateable');
    }

    /**
     * ratingsAvg function
     *
     * @return integer
     */
    public function ratingsAvg(): int
    {
        return $this->ratings()->avg('value');
    }

    /**
     * ratingsCount function
     *
     * @return integer
     */
    public function ratingsCount(): int
    {
        return $this->ratings()->count();
    }
}
