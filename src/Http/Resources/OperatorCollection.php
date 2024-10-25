<?php

namespace Module\System\Http\Resources;

use Module\System\Models\SystemOperator;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OperatorCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return OperatorResource::collection($this->collection);
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
                'combos' => SystemOperator::mapCombos($request),

                /** the page data filter */
                'filters' => SystemOperator::mapFilters(),

                /** the table header */
                'headers' => SystemOperator::mapHeaders($request),

                /** the page icon */
                'icon' => SystemOperator::getPageIcon('system-operator'),

                /** the record key */
                'key' => SystemOperator::getDataKey(),

                /** the page default */
                'recordBase' => SystemOperator::mapRecordBase($request),

                /** the page statuses */
                'statuses' => SystemOperator::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => SystemOperator::getPageTitle($request, 'system-operator'),

                /** the usetrash flag */
                'usetrash' => SystemOperator::hasSoftDeleted(),
            ]
        ];
    }
}
