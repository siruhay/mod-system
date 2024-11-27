<?php

namespace Module\System\Http\Resources;

use Module\System\Models\SystemRating;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RatingCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return RatingResource::collection($this->collection);
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
                'combos' => SystemRating::mapCombos($request),

                /** the page data filter */
                'filters' => SystemRating::mapFilters(),

                /** the table header */
                'headers' => SystemRating::mapHeaders($request),

                /** the page icon */
                'icon' => SystemRating::getPageIcon('system-rating'),

                /** the record key */
                'key' => SystemRating::getDataKey(),

                /** the page default */
                'recordBase' => SystemRating::mapRecordBase($request),

                /** the page statuses */
                'statuses' => SystemRating::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => SystemRating::getPageTitle($request, 'system-rating'),

                /** the usetrash flag */
                'usetrash' => SystemRating::hasSoftDeleted(),
            ]
        ];
    }
}
