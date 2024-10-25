<?php

namespace Module\System\Http\Resources;

use Module\System\Models\SystemPoll;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PollCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return PollResource::collection($this->collection);
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function with($request): array
    {
        if ($request->has('initialized')) {
            return [];
        }

        return [
            'setups' => [
                /** the page combo */
                'combos' => SystemPoll::mapCombos($request),

                /** the page data filter */
                'filters' => SystemPoll::mapFilters(),

                /** the table header */
                'headers' => SystemPoll::mapHeaders($request),

                /** the page icon */
                'icon' => SystemPoll::getPageIcon('system-poll'),

                /** the record key */
                'key' => SystemPoll::getDataKey(),

                /** the page default */
                'recordBase' => SystemPoll::mapRecordBase($request),

                /** the page statuses */
                'statuses' => SystemPoll::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => SystemPoll::getPageTitle($request, 'system-poll'),

                /** the usetrash flag */
                'usetrash' => SystemPoll::hasSoftDeleted(),
            ]
        ];
    }
}
