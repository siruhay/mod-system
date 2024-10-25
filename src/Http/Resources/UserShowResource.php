<?php

namespace Module\System\Http\Resources;

use Module\System\Models\SystemUser;
use Illuminate\Http\Resources\Json\JsonResource;
use Module\System\Http\Resources\UserLogActivity;

class UserShowResource extends JsonResource
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
            'record' => SystemUser::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => SystemUser::mapCombos($request, $this),

                'icon' => SystemUser::getPageIcon('system-user'),

                'key' => SystemUser::getDataKey(),

                'logs' => $request->activities ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => SystemUser::mapStatuses($request, $this),

                'title' => SystemUser::getPageTitle($request, 'system-user'),
            ],
        ];
    }
}
