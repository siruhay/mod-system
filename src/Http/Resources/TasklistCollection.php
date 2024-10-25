<?php

namespace Module\System\Http\Resources;

use Module\System\Models\SystemTasklist;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TasklistCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return TasklistResource::collection($this->collection);
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
                'combos' => SystemTasklist::mapCombos($request),

                /** the page data filter */
                'filters' => SystemTasklist::mapFilters(),

                /** the table header */
                'headers' => SystemTasklist::mapHeaders($request),

                /** the page icon */
                'icon' => SystemTasklist::getPageIcon('system-tasklist'),

                /** the record key */
                'key' => SystemTasklist::getDataKey(),

                /** the page default */
                'recordBase' => SystemTasklist::mapRecordBase($request),

                /** the page statuses */
                'statuses' => SystemTasklist::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => SystemTasklist::getPageTitle($request, 'system-tasklist'),

                /** the usetrash flag */
                'usetrash' => SystemTasklist::hasSoftDeleted(),
            ]
        ];
    }
}
