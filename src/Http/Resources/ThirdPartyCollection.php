<?php

namespace Module\System\Http\Resources;

use Module\System\Models\SystemThirdParty;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ThirdPartyCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return ThirdPartyResource::collection($this->collection);
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
                'combos' => SystemThirdParty::mapCombos($request),

                /** the page data filter */
                'filters' => SystemThirdParty::mapFilters(),

                /** the table header */
                'headers' => SystemThirdParty::mapHeaders($request),

                /** the page icon */
                'icon' => SystemThirdParty::getPageIcon('system-thirdparty'),

                /** the record key */
                'key' => SystemThirdParty::getDataKey(),

                /** the page default */
                'recordBase' => SystemThirdParty::mapRecordBase($request),

                /** the page statuses */
                'statuses' => SystemThirdParty::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => SystemThirdParty::getPageTitle($request, 'system-thirdparty'),

                /** the usetrash flag */
                'usetrash' => SystemThirdParty::hasSoftDeleted(),
            ]
        ];
    }
}
