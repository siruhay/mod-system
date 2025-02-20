<?php

namespace Module\System\Models;

use Illuminate\Http\Request;
use Module\System\Traits\HasMeta;
use Illuminate\Support\Facades\DB;
use Module\System\Traits\Filterable;
use Module\System\Traits\Searchable;
use Module\System\Models\SystemModule;
use Module\System\Traits\HasPageSetup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Module\System\Http\Resources\AbilityResource;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class SystemAbility extends Model
{
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
    protected $table = 'system_abilities';

    /**
     * The roles variable
     *
     * @var array
     */
    protected $roles = ['system-ability'];

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
        return 'module-' . SystemModule::find($request->segment(4))->name;
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
            'name' => null,
            'module_id' => intval($request->segment(4)),
            'role_id' => null,
        ];
    }

    /**
     * mapCombos function
     *
     * @param Request $request
     * @return array
     */
    public static function mapCombos(Request $request): array
    {
        return [
            'modules' => SystemModule::where('id', $request->segment(4))->forCombo(),
            'roles' => SystemRole::forCombo()
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
            'module_id' => $model->module_id,
            'role_id' => $model->role_id,
            'updated_at' => (string) $model->updated_at,
        ];
    }

    /**
     * module function
     *
     * @return BelongsTo
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(SystemModule::class, 'module_id');
    }

    /**
     * The pages function
     *
     * @return HasMany
     */
    public function pages(): HasMany
    {
        return $this->hasMany(SystemAbilityPage::class, 'ability_id')->orderBy('id');
    }

    /**
     * The pages function
     *
     * @return BelongsToMany
     */
    public function abilityPages(): BelongsToMany
    {
        return $this->belongsToMany(SystemPage::class, 'system_ability_pages', 'ability_id', 'page_id')->orderBy('_lft')->withPivot('id');
    }

    /**
     * licenses function
     *
     * @return HasMany
     */
    public function licenses(): HasMany
    {
        return $this->hasMany(SystemLicense::class, 'ability_id');
    }

    /**
     * The permissions function
     *
     * @return HasMany
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(SystemAbilityPermission::class, 'ability_id');
    }

    /**
     * userpermissions function
     *
     * @return HasManyThrough
     */
    public function userpermissions(): HasManyThrough
    {
        return $this->hasManyThrough(
            SystemPermission::class,
            SystemAbilityPermission::class,
            'ability_id',
            'id'
        );
    }

    /**
     * role function
     *
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(SystemRole::class, 'role_id');
    }

    /**
     * The model store method
     *
     * @param Request $request
     * @return void
     */
    public static function storeRecord(Request $request, SystemModule $parent)
    {
        $model = new static();
        $role = SystemRole::find($request->role_id);

        DB::connection($model->connection)->beginTransaction();

        try {
            $model->name = $parent->slug . '-' . optional($role)->slug;
            $model->role_id = optional($role)->id;

            $parent->abilities()->save($model);

            DB::connection($model->connection)->commit();

            return new AbilityResource($model);
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
        $module = $model->module;
        $role = SystemRole::find($request->role_id);

        DB::connection($model->connection)->beginTransaction();

        try {
            $model->name = $module->slug . '-' . optional($role)->slug;
            $model->role_id = optional($role)->id;
            $model->save();

            DB::connection($model->connection)->commit();

            return new AbilityResource($model);
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

            return response()->json(['success' => true]);
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

            return response()->json(['success' => true]);
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

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::connection($model->connection)->rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
