<?php

namespace Module\System\Models;

use Illuminate\Http\Request;
use Module\System\Traits\HasMeta;
use Illuminate\Support\Facades\DB;
use Module\System\Traits\Auditable;
use Module\System\Traits\Filterable;
use Module\System\Traits\Searchable;
use Module\System\Traits\HasPageSetup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Module\System\Http\Resources\AbilityPageResource;

class SystemAbilityPage extends Model
{
    use Auditable;
    use Filterable;
    use HasMeta;
    use HasPageSetup;
    use Searchable;
    use SoftDeletes;

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'platform';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_ability_pages';

    /**
     * The roles variable
     *
     * @var array
     */
    protected $roles = ['system-abilitypage'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'meta' => 'array'
    ];

    /**
     * The default key for the order.
     *
     * @var string
     */
    protected $defaultOrder = 'name';

    /**
     * getPageTitle function
     *
     * @param [type] $slug
     * @return void
     */
    public static function getPageTitle(Request $request, $slug): string | null
    {
        return SystemAbility::find($request->segment(4))->name;
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
            'name' => optional($model->page)->name,
            'ability_id' => $model->ability_id,
            'module_id' => $model->module_id,

            'page_id' => $model->page_id,
            'page_parent' => optional(optional($model->page)->parent)->path ?: '/',
            'page_path' => optional($model->page)->path,
            
            'updated_at' => (string) $model->updated_at,
        ];
    }

    /**
     * mapResource function
     *
     * @param Request $request
     * @return array
     */
    public static function mapResourceShow(Request $request, $model): array
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'ability_id' => $model->ability_id,
            'module_id' => $model->module_id,
            'module_name' => optional($model->module)->name,
            'page_id' => $model->page_id,
            'page_name' => optional($model->page)->name,
            'permissions' => static::mapPermissions(
                optional(optional($model->ability)->role)->slug, 
                $model->page->permissions, 
                $model->permissions
            ),
            'updated_at' => (string) $model->updated_at,
        ];
    }

    /**
     * mapPermissions function
     *
     * @param [type] $basePermissions
     * @param [type] $pagePermissions
     * @return void
     */
    public static function mapPermissions($role, $basePermissions, $pagePermissions)
    {
        return $basePermissions->map(function ($permission) use ($role, $pagePermissions) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'slug' => $permission->slug,
                'role' => $role,
                'value' => $pagePermissions->contains('permission_id', $permission->id)
            ];
        });
    }

    /**
     * The ability function
     *
     * @return BelongsTo
     */
    public function ability(): BelongsTo
    {
        return $this->belongsTo(SystemAbility::class, 'ability_id');
    }

    /**
     * The module function
     *
     * @return BelongsTo
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(SystemModule::class, 'module_id');
    }

    /**
     * The page function
     *
     * @return BelongsTo
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(SystemPage::class, 'page_id');
    }

    /**
     * permissions function
     *
     * @return HasMany
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(SystemAbilityPermission::class, 'ability_page_id');
    }

    /**
     * The model store method
     *
     * @param Request $request
     * @return void
     */
    public static function storeRecord(Request $request, SystemAbility $parent)
    {
        $model = new static();

        DB::connection($model->connection)->beginTransaction();

        try {
            // ...
            $parent->pages()->save($model);

            DB::connection($model->connection)->commit();

            return new AbilityPageResource($model);
        } catch (\Exception $e) {
            DB::connection($model->connection)->rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * The model update method
     *
     * @param Request $request
     * @param [type] $model
     * @return void
     */
    public static function updateRecord(Request $request, $model)
    {
        DB::connection($model->connection)->beginTransaction();

        try {
            foreach ($request->permissions as $permission) {
                $abilityPermission = $model
                    ->permissions()
                    ->where('permission_id', $permission['id'])
                    ->first();

                if (!$permission['value'] && !$abilityPermission) {
                    continue;
                }

                if (!$permission['value'] && $abilityPermission) {
                    $abilityPermission->delete();
                    continue;
                }

                if ($permission['value'] && $abilityPermission) {
                    continue;
                }

                $record = new SystemAbilityPermission();
                $record->name = $permission['role'] . '-' . $permission['slug'];
                $record->ability_id = $model->ability_id;
                $record->ability_page_id = $model->id;
                $record->module_id = $model->module_id;
                $record->page_id = $model->page_id;
                $record->permission_id = $permission['id'];
                $record->save();
            }

            $model->meta = $request->permissions;
            $model->save();

            DB::connection($model->connection)->commit();

            return new AbilityPageResource($model);
        } catch (\Exception $e) {
            DB::connection($model->connection)->rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * The model delete method
     *
     * @param [type] $model
     * @return void
     */
    public static function deleteRecord($model)
    {
        DB::connection($model->connection)->beginTransaction();

        try {
            $model->delete();

            DB::connection($model->connection)->commit();

            return new AbilityPageResource($model);
        } catch (\Exception $e) {
            DB::connection($model->connection)->rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * The model restore method
     *
     * @param [type] $model
     * @return void
     */
    public static function restoreRecord($model)
    {
        DB::connection($model->connection)->beginTransaction();

        try {
            $model->restore();

            DB::connection($model->connection)->commit();

            return new AbilityPageResource($model);
        } catch (\Exception $e) {
            DB::connection($model->connection)->rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * The model destroy method
     *
     * @param [type] $model
     * @return void
     */
    public static function destroyRecord($model)
    {
        DB::connection($model->connection)->beginTransaction();

        try {
            $model->forceDelete();

            DB::connection($model->connection)->commit();

            return new AbilityPageResource($model);
        } catch (\Exception $e) {
            DB::connection($model->connection)->rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}