<?php

namespace Module\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\System\Models\SystemThirdParty;
use Module\System\Http\Resources\UserLogActivity;

class ThirdPartyShowResource extends JsonResource
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
            'record' => SystemThirdParty::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => SystemThirdParty::mapCombos($request, $this),

                'icon' => SystemThirdParty::getPageIcon('system-thirdparty'),

                'key' => SystemThirdParty::getDataKey(),

                'logs' => $request->activities ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => SystemThirdParty::mapStatuses($request, $this),

                'title' => SystemThirdParty::getPageTitle($request, 'system-thirdparty'),
            ],
        ];
    }
}
