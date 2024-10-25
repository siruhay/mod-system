<?php

namespace Module\System\Http\Resources;

use Module\System\Models\SystemVote;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VoteCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return VoteResource::collection($this->collection);
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
                'combos' => SystemVote::mapCombos($request),

                /** the page data filter */
                'filters' => SystemVote::mapFilters(),

                /** the table header */
                'headers' => SystemVote::mapHeaders($request),

                /** the page icon */
                'icon' => SystemVote::getPageIcon('system-vote'),

                /** the record key */
                'key' => SystemVote::getDataKey(),

                /** the page default */
                'recordBase' => SystemVote::mapRecordBase($request),

                /** the page statuses */
                'statuses' => SystemVote::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => SystemVote::getPageTitle($request, 'system-vote'),

                /** the usetrash flag */
                'usetrash' => SystemVote::hasSoftDeleted(),
            ]
        ];
    }
}
