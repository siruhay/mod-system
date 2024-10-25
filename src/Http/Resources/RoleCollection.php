<?php

namespace Module\System\Http\Resources;

use Module\System\Models\SystemRole;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RoleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return RoleResource::collection($this->collection);
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
                'combos' => SystemRole::mapCombos($request),

                /** the page data filter */
                'filters' => SystemRole::mapFilters(),

                /** the table header */
                'headers' => SystemRole::mapHeaders($request),

                /** the page icon */
                'icon' => SystemRole::getPageIcon('system-role'),

                /** the record key */
                'key' => SystemRole::getDataKey(),

                /** the page default */
                'recordBase' => SystemRole::mapRecordBase($request),

                /** the page statuses */
                'statuses' => SystemRole::mapStatuses($request),

                /** the page data mode */
                /** default | trashed */
                'trashed' => $request->trashed ?: false,
                
                /** the page title */
                'title' => SystemRole::getPageTitle($request, 'system-role'),

                /** the usetrash flag */
                'usetrash' => SystemRole::hasSoftDeleted(),
            ]
        ];
    }
}
