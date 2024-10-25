<?php

namespace Module\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\System\Models\SystemPermission;
use Module\System\Http\Resources\UserLogActivity;

class PermissionShowResource extends JsonResource
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
            'record' => SystemPermission::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => SystemPermission::mapCombos($request, $this),

                'icon' => SystemPermission::getPageIcon('system-permission'),

                'key' => SystemPermission::getDataKey(),

                'logs' => $request->activities && $this->activitylogs ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => SystemPermission::mapStatuses($request, $this),

                'title' => SystemPermission::getPageTitle($request, 'system-permission'),
            ],
        ];
    }
}
