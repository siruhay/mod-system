<?php

namespace Module\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\System\Models\SystemPersonate;
use Module\System\Http\Resources\UserLogActivity;

class PersonateShowResource extends JsonResource
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
            'record' => SystemPersonate::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => SystemPersonate::mapCombos($request, $this),

                'icon' => SystemPersonate::getPageIcon('system-personate'),

                'key' => SystemPersonate::getDataKey(),

                'logs' => $request->activities ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => SystemPersonate::mapStatuses($request, $this),

                'title' => SystemPersonate::getPageTitle($request, 'system-personate'),
            ],
        ];
    }
}
