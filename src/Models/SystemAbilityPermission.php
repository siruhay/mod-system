<?php

namespace Module\System\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Module\System\Models\SystemPage;
use Module\System\Traits\Filterable;
use Module\System\Traits\Searchable;
use Module\System\Traits\HasPageSetup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemAbilityPermission extends Model
{
    use Filterable;
    use HasPageSetup;
    use Searchable;

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
    protected $table = 'system_ability_permissions';

    /**
     * The roles variable
     *
     * @var array
     */
    protected $roles = ['system-abilitypermission'];

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
     * ability function
     *
     * @return BelongsTo
     */
    public function ability(): BelongsTo
    {
        return $this->belongsTo(SystemAbility::class, 'ability_id');
    }

    /**
     * abilityPage function
     *
     * @return BelongsTo
     */
    public function abilityPage(): BelongsTo
    {
        return $this->belongsTo(SystemAbilityPage::class, 'ability_page_id');
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
     * page function
     *
     * @return BelongsTo
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(SystemPage::class, 'page_id');
    }

    /**
     * permission function
     *
     * @return BelongsTo
     */
    public function permission(): BelongsTo
    {
        return $this->belongsTo(SystemPermission::class, 'permission_id');
    }

    /**
     * The model store method
     *
     * @param Request $request
     * @return void
     */
    public static function storeRecord(Request $request, SystemPage $parent)
    {
        $model = new static();

        DB::connection($model->connection)->beginTransaction();

        try {
            // ...
            $parent->abilitypermissions()->save($model);

            DB::connection($model->connection)->commit();

            // return new AbilityPermissionResource($model);
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
            // ...
            $model->save();

            DB::connection($model->connection)->commit();

            // return new AbilityPermissionResource($model);
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