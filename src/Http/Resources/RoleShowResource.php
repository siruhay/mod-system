<?php

namespace Module\System\Http\Resources;

use Module\System\Models\SystemRole;
use Illuminate\Http\Resources\Json\JsonResource;
use Module\System\Http\Resources\UserLogActivity;

class RoleShowResource extends JsonResource
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
            'record' => SystemRole::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => SystemRole::mapCombos($request, $this),

                'icon' => SystemRole::getPageIcon('system-role'),

                'logs' => $request->activities ? UserLogActivity::collection($this->activitylogs) : null,

                'key' => SystemRole::getDataKey(),

                'softdelete' => $this->trashed() ?: false,

                'statuses' => SystemRole::mapStatuses($request, $this),

                'title' => SystemRole::getPageTitle($request, 'system-role'),
            ],
        ];
    }
}
