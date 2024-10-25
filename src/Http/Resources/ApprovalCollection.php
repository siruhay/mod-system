<?php

namespace Module\System\Http\Resources;

use Module\System\Models\SystemApproval;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ApprovalCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return ApprovalResource::collection($this->collection);
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
                'combos' => SystemApproval::mapCombos($request),

                /** the page data filter */
                'filters' => SystemApproval::mapFilters(),

                /** the table header */
                'headers' => SystemApproval::mapHeaders($request),

                /** the page icon */
                'icon' => SystemApproval::getPageIcon('system-approval'),

                /** the record key */
                'key' => SystemApproval::getDataKey(),

                /** the page default */
                'recordBase' => SystemApproval::mapRecordBase($request),

                /** the page statuses */
                'statuses' => SystemApproval::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => SystemApproval::getPageTitle($request, 'system-approval'),

                /** the usetrash flag */
                'usetrash' => SystemApproval::hasSoftDeleted(),
            ]
        ];
    }
}
