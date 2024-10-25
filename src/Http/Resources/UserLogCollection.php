<?php

namespace Module\System\Http\Resources;

use Module\System\Models\SystemUserLog;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserLogCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return UserLogResource::collection($this->collection);
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
                'combos' => SystemUserLog::mapCombos($request),

                /** the page data filter */
                'filters' => SystemUserLog::mapFilters(),

                /** the table header */
                'headers' => SystemUserLog::mapHeaders($request),

                /** the page icon */
                'icon' => SystemUserLog::getPageIcon('system-userlog'),

                /** the record key */
                'key' => SystemUserLog::getDataKey(),

                /** the page default */
                'recordBase' => SystemUserLog::mapRecordBase($request),

                /** the page statuses */
                'statuses' => SystemUserLog::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => SystemUserLog::getPageTitle($request, 'system-userlog'),

                /** the usetrash flag */
                'usetrash' => SystemUserLog::hasSoftDeleted(),
            ]
        ];
    }
}
