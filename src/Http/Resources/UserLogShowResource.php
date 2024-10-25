<?php

namespace Module\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\System\Models\SystemUserLog;

class UserLogShowResource extends JsonResource
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
            'record' => SystemUserLog::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => SystemUserLog::mapCombos($request, $this),

                'icon' => SystemUserLog::getPageIcon('system-userlog'),

                'key' => SystemUserLog::getDataKey(),

                'softdelete' => $this->trashed() ?: false,

                'statuses' => SystemUserLog::mapStatuses($request, $this),

                'title' => SystemUserLog::getPageTitle($request, 'system-userlog'),
            ],
        ];
    }
}
