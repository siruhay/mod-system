<?php

namespace Module\System\Traits;

use Illuminate\Http\Request;

trait HasPageSetup
{
    /**
     * getDataKey function
     *
     * @return void
     */
    public static function getDataKey(): string | null
    {
        return (new static())->primaryKey;
    }

    /**
     * getPageIcon function
     *
     * @param [type] $slug
     * @return void
     */
    public static function getPageIcon($slug): string | null
    {
        return null;
    }

    /**
     * getPageTitle function
     *
     * @param [type] $slug
     * @return void
     */
    public static function getPageTitle(Request $request, $slug): string | null
    {
        return null;
    }

    /**
     * mapCombos function
     *
     * @param Request $request
     * @return array
     */
    public static function mapCombos(Request $request): array
    {
        return [];
    }

    /**
     * mapHeaders function
     *
     * readonly value?: SelectItemKey<any>
     * readonly title?: string | undefined
     * readonly align?: 'start' | 'end' | 'center' | undefined
     * readonly width?: string | number | undefined
     * readonly minWidth?: string | undefined
     * readonly maxWidth?: string | undefined
     * readonly nowrap?: boolean | undefined
     * readonly sortable?: boolean | undefined
     *
     * @param Request $request
     * @return array
     */
    public static function mapHeaders(Request $request): array
    {
        return [
            ['title' => 'Name', 'value' => 'name'],
            ['title' => 'Updated', 'value' => 'updated_at', 'sortable' => false, 'width' => '170'],
        ];
    }

    /**
     * mapRecordBase function
     *
     * @param Request $request
     * @return array
     */
    public static function mapRecordBase(Request $request): array
    {
        return [
            'id' => null,
            'name' => null
        ];
    }

    /**
     * mapResource function
     *
     * @param Request $request
     * @return array
     */
    public static function mapResource(Request $request, $model): array
    {
        return [
            'id' => $model->id,
            'name' => $model->name,

            'subtitle' => (string) $model->updated_at,
            'updated_at' => (string) $model->updated_at,
        ];
    }

    /**
     * mapResourceShow function
     *
     * @param Request $request
     * @return array
     */
    public static function mapResourceShow(Request $request, $model): array
    {
        return static::mapResource($request, $model);
    }

    /**
     * mapStatuses function
     *
     * @param Request $request
     * @return array
     */
    public static function mapStatuses(Request $request): array
    {
        return [];
    }
}
