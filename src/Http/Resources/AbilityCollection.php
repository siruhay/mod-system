<?php

namespace Module\System\Http\Resources;

use Module\System\Models\SystemAbility;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AbilityCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return AbilityResource::collection($this->collection);
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
                'combos' => SystemAbility::mapCombos($request),

                /** the page data filter */
                'filters' => SystemAbility::mapFilters(),

                /** the table header */
                'headers' => SystemAbility::mapHeaders($request),

                /** the page icon */
                'icon' => SystemAbility::getPageIcon('system-ability'),

                /** the record key */
                'key' => SystemAbility::getDataKey(),

                /** the page default */
                'recordBase' => SystemAbility::mapRecordBase($request),

                /** the page statuses */
                'statuses' => SystemAbility::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => SystemAbility::getPageTitle($request, 'system-ability'),

                /** the usetrash flag */
                'usetrash' => SystemAbility::hasSoftDeleted(),
            ]
        ];
    }
}
