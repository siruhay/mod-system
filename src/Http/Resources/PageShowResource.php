<?php

namespace Module\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\System\Models\SystemPage;
use Module\System\Http\Resources\UserLogActivity;

class PageShowResource extends JsonResource
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
            'record' => SystemPage::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => SystemPage::mapCombos($request, $this),

                'icon' => SystemPage::getPageIcon('system-page'),

                'key' => SystemPage::getDataKey(),

                'logs' => $request->activities && $this->activitylogs ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => SystemPage::mapStatuses($request, $this),

                'title' => SystemPage::getPageTitle($request, 'system-page'),
            ],
        ];
    }
}
