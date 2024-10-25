<?php

namespace Module\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\System\Models\SystemLicense;
use Module\System\Http\Resources\UserLogActivity;

class LicenseShowResource extends JsonResource
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
            'record' => SystemLicense::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => SystemLicense::mapCombos($request, $this),

                'icon' => SystemLicense::getPageIcon('system-license'),

                'key' => SystemLicense::getDataKey(),

                'logs' => $request->activities && $this->activitylogs ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => SystemLicense::mapStatuses($request, $this),

                'title' => SystemLicense::getPageTitle($request, 'system-license'),
            ],
        ];
    }
}
