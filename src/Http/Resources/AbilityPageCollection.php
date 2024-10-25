<?php

namespace Module\System\Http\Resources;

use Module\System\Models\SystemAbilityPage;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AbilityPageCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return AbilityPageResource::collection($this->collection);
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
                'combos' => SystemAbilityPage::mapCombos($request),

                /** the page data filter */
                'filters' => SystemAbilityPage::mapFilters(),

                /** the table header */
                'headers' => SystemAbilityPage::mapHeaders($request),

                /** the page icon */
                'icon' => SystemAbilityPage::getPageIcon('system-abilitypage'),

                /** the record key */
                'key' => SystemAbilityPage::getDataKey(),

                /** the page default */
                'recordBase' => SystemAbilityPage::mapRecordBase($request),

                /** the page statuses */
                'statuses' => SystemAbilityPage::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => SystemAbilityPage::getPageTitle($request, 'system-abilitypage'),

                /** the usetrash flag */
                'usetrash' => SystemAbilityPage::hasSoftDeleted(),
            ]
        ];
    }
}
