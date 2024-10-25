<?php

namespace Module\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\System\Models\SystemAuditor;
use Module\System\Http\Resources\UserLogActivity;

class AuditorShowResource extends JsonResource
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
            'record' => SystemAuditor::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => SystemAuditor::mapCombos($request, $this),

                'icon' => SystemAuditor::getPageIcon('system-auditor'),

                'key' => SystemAuditor::getDataKey(),

                'logs' => $request->activities && $this->activitylogs ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => SystemAuditor::mapStatuses($request, $this),

                'title' => SystemAuditor::getPageTitle($request, 'system-auditor'),
            ],
        ];
    }
}
