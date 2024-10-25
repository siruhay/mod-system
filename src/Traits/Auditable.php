<?php

namespace Module\System\Traits;

use Illuminate\Database\Eloquent\Model;
use Module\System\Models\SystemUserLog;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Auditable
{
    /**
     * The "booted" method of the model.
     */
    public static function bootAuditable(): void
    {
        static::approved(function (Model $model) {
            SystemUserLog::eventLog('approved', $model);
        });

        static::confirmed(function (Model $model) {
            SystemUserLog::eventLog('confirmed', $model);
        });

        static::created(function (Model $model) {
            SystemUserLog::eventLog('created', $model);
        });

        static::deleted(function (Model $model) {
            if ($model->forceDeleting) {
                SystemUserLog::eventLog('deleted', $model);
            } else {
                SystemUserLog::eventLog('trashed', $model);
            }
        });

        static::determined(function (Model $model) {
            SystemUserLog::eventLog('determined', $model);
        });

        static::drafted(function (Model $model) {
            SystemUserLog::eventLog('drafted', $model);
        });

        static::failed(function (Model $model) {
            SystemUserLog::eventLog('failed', $model);
        });

        static::finalized(function (Model $model) {
            SystemUserLog::eventLog('finalized', $model);
        });

        static::pending(function (Model $model) {
            SystemUserLog::eventLog('pending', $model);
        });

        static::posted(function (Model $model) {
            SystemUserLog::eventLog('posted', $model);
        });

        static::printed(function (Model $model) {
            SystemUserLog::eventLog('printed', $model);
        });

        static::published(function (Model $model) {
            SystemUserLog::eventLog('published', $model);
        });

        static::proposed(function (Model $model) {
            SystemUserLog::eventLog('proposed', $model);
        });

        static::rejected(function (Model $model) {
            SystemUserLog::eventLog('rejected', $model);
        });

        static::repaired(function (Model $model) {
            SystemUserLog::eventLog('repaired', $model);
        });

        static::restored(function (Model $model) {
            SystemUserLog::eventLog('restored', $model);
        });

        static::signed(function (Model $model) {
            SystemUserLog::eventLog('signed', $model);
        });

        static::submitted(function (Model $model) {
            SystemUserLog::eventLog('submitted', $model);
        });

        static::synced(function (Model $model) {
            SystemUserLog::eventLog('synced', $model);
        });

        static::updated(function (Model $model) {
            SystemUserLog::eventLog('updated', $model);
        });

        static::verified(function (Model $model) {
            SystemUserLog::eventLog('verified', $model);
        });
    }

    /**
     * Register a approved model event with the dispatcher.
     *
     * @param  \Illuminate\Events\QueuedClosure|\Closure|string  $callback
     * @return void
     */
    public static function approved($callback)
    {
        static::registerModelEvent('approved', $callback);
    }

    /**
     * Register a confirmed model event with the dispatcher.
     *
     * @param  \Illuminate\Events\QueuedClosure|\Closure|string  $callback
     * @return void
     */
    public static function confirmed($callback)
    {
        static::registerModelEvent('confirmed', $callback);
    }

    /**
     * Register a determined model event with the dispatcher.
     *
     * @param  \Illuminate\Events\QueuedClosure|\Closure|string  $callback
     * @return void
     */
    public static function determined($callback)
    {
        static::registerModelEvent('determined', $callback);
    }

    /**
     * Register a drafted model event with the dispatcher.
     *
     * @param  \Illuminate\Events\QueuedClosure|\Closure|string  $callback
     * @return void
     */
    public static function drafted($callback)
    {
        static::registerModelEvent('drafted', $callback);
    }

    /**
     * Register a failed model event with the dispatcher.
     *
     * @param  \Illuminate\Events\QueuedClosure|\Closure|string  $callback
     * @return void
     */
    public static function failed($callback)
    {
        static::registerModelEvent('failed', $callback);
    }

    /**
     * Register a finalized model event with the dispatcher.
     *
     * @param  \Illuminate\Events\QueuedClosure|\Closure|string  $callback
     * @return void
     */
    public static function finalized($callback)
    {
        static::registerModelEvent('finalized', $callback);
    }

    /**
     * Register a pending model event with the dispatcher.
     *
     * @param  \Illuminate\Events\QueuedClosure|\Closure|string  $callback
     * @return void
     */
    public static function pending($callback)
    {
        static::registerModelEvent('pending', $callback);
    }

    /**
     * Register a posted model event with the dispatcher.
     *
     * @param  \Illuminate\Events\QueuedClosure|\Closure|string  $callback
     * @return void
     */
    public static function posted($callback)
    {
        static::registerModelEvent('posted', $callback);
    }

    /**
     * Register a printed model event with the dispatcher.
     *
     * @param  \Illuminate\Events\QueuedClosure|\Closure|string  $callback
     * @return void
     */
    public static function printed($callback)
    {
        static::registerModelEvent('printed', $callback);
    }

    /**
     * Register a published model event with the dispatcher.
     *
     * @param  \Illuminate\Events\QueuedClosure|\Closure|string  $callback
     * @return void
     */
    public static function published($callback)
    {
        static::registerModelEvent('published', $callback);
    }

    /**
     * Register a proposed model event with the dispatcher.
     *
     * @param  \Illuminate\Events\QueuedClosure|\Closure|string  $callback
     * @return void
     */
    public static function proposed($callback)
    {
        static::registerModelEvent('proposed', $callback);
    }

    /**
     * Register a rejected model event with the dispatcher.
     *
     * @param  \Illuminate\Events\QueuedClosure|\Closure|string  $callback
     * @return void
     */
    public static function rejected($callback)
    {
        static::registerModelEvent('rejected', $callback);
    }

    /**
     * Register a repaired model event with the dispatcher.
     *
     * @param  \Illuminate\Events\QueuedClosure|\Closure|string  $callback
     * @return void
     */
    public static function repaired($callback)
    {
        static::registerModelEvent('repaired', $callback);
    }

    /**
     * Register a signed model event with the dispatcher.
     *
     * @param  \Illuminate\Events\QueuedClosure|\Closure|string  $callback
     * @return void
     */
    public static function signed($callback)
    {
        static::registerModelEvent('signed', $callback);
    }

    /**
     * Register a submitted model event with the dispatcher.
     *
     * @param  \Illuminate\Events\QueuedClosure|\Closure|string  $callback
     * @return void
     */
    public static function submitted($callback)
    {
        static::registerModelEvent('submitted', $callback);
    }

    /**
     * Register a synced model event with the dispatcher.
     *
     * @param  \Illuminate\Events\QueuedClosure|\Closure|string  $callback
     * @return void
     */
    public static function synced($callback)
    {
        static::registerModelEvent('synced', $callback);
    }

    /**
     * Register a verified model event with the dispatcher.
     *
     * @param  \Illuminate\Events\QueuedClosure|\Closure|string  $callback
     * @return void
     */
    public static function verified($callback)
    {
        static::registerModelEvent('verified', $callback);
    }

    /**
     * The activitylogs function
     *
     * @return MorphMany
     */
    public function activitylogs(): MorphMany
    {
        return $this->morphMany(SystemUserLog::class, 'auditable');
    }
}
