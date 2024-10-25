<?php

namespace Module\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\System\Models\SystemVote;
use Module\System\Http\Resources\UserLogActivity;

class VoteShowResource extends JsonResource
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
            'record' => SystemVote::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => SystemVote::mapCombos($request, $this),

                'icon' => SystemVote::getPageIcon('system-vote'),

                'key' => SystemVote::getDataKey(),

                'logs' => $request->activities ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => SystemVote::mapStatuses($request, $this),

                'title' => SystemVote::getPageTitle($request, 'system-vote'),
            ],
        ];
    }
}
