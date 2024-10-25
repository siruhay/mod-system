<?php

namespace Module\System\Http\Resources;

use Module\System\Models\SystemAuditor;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AuditorCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return AuditorResource::collection($this->collection);
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
                'combos' => SystemAuditor::mapCombos($request),

                /** the page data filter */
                'filters' => SystemAuditor::mapFilters(),

                /** the table header */
                'headers' => SystemAuditor::mapHeaders($request),

                /** the page icon */
                'icon' => SystemAuditor::getPageIcon('system-auditor'),

                /** the record key */
                'key' => SystemAuditor::getDataKey(),

                /** the page default */
                'recordBase' => SystemAuditor::mapRecordBase($request),

                /** the page statuses */
                'statuses' => SystemAuditor::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => SystemAuditor::getPageTitle($request, 'system-auditor'),

                /** the usetrash flag */
                'usetrash' => SystemAuditor::hasSoftDeleted(),
            ]
        ];
    }
}
