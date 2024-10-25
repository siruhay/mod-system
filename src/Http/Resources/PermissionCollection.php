<?php

namespace Module\System\Http\Resources;

use Module\System\Models\SystemPermission;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PermissionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return PermissionResource::collection($this->collection);
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
                'combos' => SystemPermission::mapCombos($request),

                /** the page data filter */
                'filters' => SystemPermission::mapFilters(),

                /** the table header */
                'headers' => SystemPermission::mapHeaders($request),

                /** the page icon */
                'icon' => SystemPermission::getPageIcon('system-permission'),

                /** the record key */
                'key' => SystemPermission::getDataKey(),

                /** the page default */
                'recordBase' => SystemPermission::mapRecordBase($request),

                /** the page statuses */
                'statuses' => SystemPermission::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => SystemPermission::getPageTitle($request, 'system-permission'),

                /** the usetrash flag */
                'usetrash' => SystemPermission::hasSoftDeleted(),
            ]
        ];
    }
}
