<?php

namespace Module\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\System\Models\SystemRating;
use Module\System\Http\Resources\UserLogActivity;

class RatingShowResource extends JsonResource
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
            'record' => SystemRating::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => SystemRating::mapCombos($request, $this),

                'icon' => SystemRating::getPageIcon('system-rating'),

                'key' => SystemRating::getDataKey(),

                'logs' => $request->activities ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => SystemRating::mapStatuses($request, $this),

                'title' => SystemRating::getPageTitle($request, 'system-rating'),
            ],
        ];
    }
}
