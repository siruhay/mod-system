<?php

namespace Module\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\System\Models\SystemModule;
use Module\System\Http\Resources\UserLogActivity;

class ModuleShowResource extends JsonResource
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
            'record' => SystemModule::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => SystemModule::mapCombos($request, $this),

                'icon' => SystemModule::getPageIcon('system-module'),

                'key' => SystemModule::getDataKey(),

                'logs' => $request->activities && $this->activitylogs ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => SystemModule::mapStatuses($request, $this),

                'title' => SystemModule::getPageTitle($request, 'system-module'),
            ],
        ];
    }
}
