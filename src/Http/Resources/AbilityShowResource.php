<?php

namespace Module\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\System\Models\SystemAbility;
use Module\System\Http\Resources\UserLogActivity;

class AbilityShowResource extends JsonResource
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
            'record' => SystemAbility::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => SystemAbility::mapCombos($request, $this),

                'icon' => SystemAbility::getPageIcon('system-ability'),

                'key' => SystemAbility::getDataKey(),

                'logs' => $request->activities && $this->activitylogs ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => SystemAbility::mapStatuses($request, $this),

                'title' => SystemAbility::getPageTitle($request, 'system-ability'),
            ],
        ];
    }
}
