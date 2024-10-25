<?php

namespace Module\System\Http\Resources;

use Module\System\Models\SystemUser;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return UserResource::collection($this->collection);
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
        if (!$request->has('initialized')) {
            return [];
        }

        return [
            'setups' => [
                /** the page combo */
                'combos' => SystemUser::mapCombos($request),

                /** the page data filter */
                'filters' => SystemUser::mapFilters(),

                /** the table header */
                'headers' => SystemUser::mapHeaders($request),

                /** the page icon */
                'icon' => SystemUser::getPageIcon('system-user'),

                /** the record key */
                'key' => SystemUser::getDataKey(),

                /** the page default */
                'recordBase' => SystemUser::mapRecordBase($request),

                /** the page statuses */
                'statuses' => SystemUser::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => SystemUser::getPageTitle($request, 'system-user'),

                /** the usetrash flag */
                'usetrash' => SystemUser::hasSoftDeleted(),
            ]
        ];
    }
}
