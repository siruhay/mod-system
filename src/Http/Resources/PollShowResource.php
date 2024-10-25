<?php

namespace Module\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\System\Models\SystemPoll;
use Module\System\Http\Resources\UserLogActivity;

class PollShowResource extends JsonResource
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
            'record' => SystemPoll::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => SystemPoll::mapCombos($request, $this),

                'icon' => SystemPoll::getPageIcon('system-poll'),

                'key' => SystemPoll::getDataKey(),

                'logs' => $request->activities ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => SystemPoll::mapStatuses($request, $this),

                'title' => SystemPoll::getPageTitle($request, 'system-poll'),
            ],
        ];
    }
}
