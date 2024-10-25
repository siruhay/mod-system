<?php

namespace Module\System\Http\Resources;

use Module\System\Models\SystemPage;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PageCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return PageResource::collection($this->collection);
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
                'combos' => SystemPage::mapCombos($request),

                /** the page data filter */
                'filters' => SystemPage::mapFilters(),

                /** the table header */
                'headers' => SystemPage::mapHeaders($request),

                /** the page icon */
                'icon' => SystemPage::getPageIcon('system-page'),

                /** the record key */
                'key' => SystemPage::getDataKey(),

                /** the page default */
                'recordBase' => SystemPage::mapRecordBase($request),

                /** the page statuses */
                'statuses' => SystemPage::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => SystemPage::getPageTitle($request, 'system-page'),

                /** the usetrash flag */
                'usetrash' => SystemPage::hasSoftDeleted(),
            ]
        ];
    }
}
