<?php

namespace Module\System\Traits;

use Module\System\Models\SystemTasklist;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Taskable
{
    /**
     * tasklists function
     *
     * @return MorphMany
     */
    public function tasklists(): MorphMany
    {
        return $this->morphMany(SystemTasklist::class, 'taskable');
    }
}
