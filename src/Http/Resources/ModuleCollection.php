<?php

namespace Module\System\Http\Resources;

use Module\System\Models\SystemModule;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ModuleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return ModuleResource::collection($this->collection);
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
                'combos' => SystemModule::mapCombos($request),

                /** the page data filter */
                'filters' => SystemModule::mapFilters(),

                /** the table header */
                'headers' => SystemModule::mapHeaders($request),

                /** the page icon */
                'icon' => SystemModule::getPageIcon('system-module'),

                /** the record key */
                'key' => SystemModule::getDataKey(),

                /** the page default */
                'recordBase' => SystemModule::mapRecordBase($request),

                /** the page statuses */
                'statuses' => SystemModule::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => SystemModule::getPageTitle($request, 'system-module'),

                /** the usetrash flag */
                'usetrash' => SystemModule::hasSoftDeleted(),
            ]
        ];
    }
}
