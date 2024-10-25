<?php

namespace Module\System\Http\Resources;

use Module\System\Models\SystemLicense;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LicenseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return LicenseResource::collection($this->collection);
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
                'combos' => SystemLicense::mapCombos($request),

                /** the page data filter */
                'filters' => SystemLicense::mapFilters(),

                /** the table header */
                'headers' => SystemLicense::mapHeaders($request),

                /** the page icon */
                'icon' => SystemLicense::getPageIcon('system-license'),

                /** the record key */
                'key' => SystemLicense::getDataKey(),

                /** the page default */
                'recordBase' => SystemLicense::mapRecordBase($request),

                /** the page statuses */
                'statuses' => SystemLicense::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => SystemLicense::getPageTitle($request, 'system-license'),

                /** the usetrash flag */
                'usetrash' => SystemLicense::hasSoftDeleted(),
            ]
        ];
    }
}
