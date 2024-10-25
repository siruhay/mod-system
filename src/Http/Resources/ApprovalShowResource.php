<?php

namespace Module\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\System\Models\SystemApproval;
use Module\System\Http\Resources\UserLogActivity;

class ApprovalShowResource extends JsonResource
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
            'record' => SystemApproval::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => SystemApproval::mapCombos($request, $this),

                'icon' => SystemApproval::getPageIcon('system-approval'),

                'key' => SystemApproval::getDataKey(),

                'logs' => $request->activities ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => SystemApproval::mapStatuses($request, $this),

                'title' => SystemApproval::getPageTitle($request, 'system-approval'),
            ],
        ];
    }
}
