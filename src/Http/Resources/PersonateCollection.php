<?php

namespace Module\System\Http\Resources;

use Module\System\Models\SystemPersonate;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PersonateCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return PersonateResource::collection($this->collection);
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
                'combos' => SystemPersonate::mapCombos($request),

                /** the page data filter */
                'filters' => SystemPersonate::mapFilters(),

                /** the table header */
                'headers' => SystemPersonate::mapHeaders($request),

                /** the page icon */
                'icon' => SystemPersonate::getPageIcon('system-personate'),

                /** the record key */
                'key' => SystemPersonate::getDataKey(),

                /** the page default */
                'recordBase' => SystemPersonate::mapRecordBase($request),

                /** the page statuses */
                'statuses' => SystemPersonate::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => SystemPersonate::getPageTitle($request, 'system-personate'),

                /** the usetrash flag */
                'usetrash' => SystemPersonate::hasSoftDeleted(),
            ]
        ];
    }
}
