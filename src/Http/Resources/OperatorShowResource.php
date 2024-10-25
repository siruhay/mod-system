<?php

namespace Module\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\System\Models\SystemOperator;
use Module\System\Http\Resources\UserLogActivity;

class OperatorShowResource extends JsonResource
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
            'record' => SystemOperator::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => SystemOperator::mapCombos($request, $this),

                'icon' => SystemOperator::getPageIcon('system-operator'),

                'key' => SystemOperator::getDataKey(),

                'logs' => $request->activities && $this->activitylogs ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => SystemOperator::mapStatuses($request, $this),

                'title' => SystemOperator::getPageTitle($request, 'system-operator'),
            ],
        ];
    }
}
