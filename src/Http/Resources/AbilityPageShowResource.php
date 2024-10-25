<?php

namespace Module\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\System\Models\SystemAbilityPage;
use Module\System\Http\Resources\UserLogActivity;

class AbilityPageShowResource extends JsonResource
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
            'record' => SystemAbilityPage::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => SystemAbilityPage::mapCombos($request, $this),

                'icon' => SystemAbilityPage::getPageIcon('system-abilitypage'),

                'key' => SystemAbilityPage::getDataKey(),

                'logs' => $request->activities && $this->activitylogs ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => SystemAbilityPage::mapStatuses($request, $this),

                'title' => SystemAbilityPage::getPageTitle($request, 'system-abilitypage'),
            ],
        ];
    }
}
