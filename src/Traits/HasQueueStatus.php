<?php

namespace Module\System\Traits;

use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Resources\Json\JsonResource;

trait HasQueueStatus
{
    /**
     * mapQueueStatus function
     *
     * @param JsonResource $model
     * @return array
     */
    protected static function mapQueueStatus(JsonResource $model): array
    {
        // if (!Schema::connection($model->connection)->hasColumn($model->table, 'synced')) {
        //     return [];
        // }

        return [
            'synced' => $model->synced,
            'syncedStatus' => static::mapQueueLabel($model),
            'syncedLog' => $model->synced_log,
        ];
    }

    /**
     * mapQueueLabel function
     *
     * @param JsonResource $model
     * @return string
     */
    protected static function mapQueueLabel(JsonResource $model): string
    {
        if ($model->synced_status) {
            return 'SYNC';
        }

        if ($model->synced && !is_null($model->synced_at)) {
            return 'DONE';
        }

        if (!$model->synced && !is_null($model->synced_at)) {
            return 'FAIL';
        }

        return 'NONE';
    }
}
