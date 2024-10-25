<?php

namespace Module\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\System\Models\SystemTasklist;
use Module\System\Http\Resources\UserLogActivity;

class TasklistShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            /**
             * the record data
             */
            'record' => SystemTasklist::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => SystemTasklist::mapCombos($request, $this),

                'icon' => SystemTasklist::getPageIcon('system-tasklist'),

                'key' => SystemTasklist::getDataKey(),

                'logs' => $request->activities ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => SystemTasklist::mapStatuses($request, $this),

                'title' => SystemTasklist::getPageTitle($request, 'system-tasklist'),
            ],
        ];
    }
}
