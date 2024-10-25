<?php

namespace Module\System\Models;

use Illuminate\Http\Request;
use Kalnoy\Nestedset\NodeTrait;
use Module\System\Traits\HasMeta;
use Illuminate\Support\Facades\DB;
use Module\System\Traits\Filterable;
use Module\System\Traits\Searchable;
use Illuminate\Support\Facades\Cache;
use Module\System\Traits\HasPageSetup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Module\System\Http\Resources\ModuleResource;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SystemModule extends Model
{
    use Filterable;
    use HasMeta;
    use HasPageSetup;
    use NodeTrait;
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
    protected $table = 'system_modules';

    /**
     * The roles variable
     *
     * @var array
     */
    protected $roles = ['system-module'];

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
            'type' => $model->type,
            'domain' => $model->domain,
            'prefix' => $model->prefix,
            'color' => $model->color,
            'highlight' => $model->highlight,
            'icon' => $model->icon,
            'desktop' => $model->desktop,
            'mobile' => $model->mobile,
            'enabled' => $model->enabled,
            'published' => $model->published,
            'git_address' => $model->git_address,

            'chip' => $model->type,
            'subtitle' => (string) $model->updated_at,
            'updated_at' => (string) $model->updated_at,
        ];
    }

    /**
     * The abilities function
     *
     * @return HasMany
     */
    public function abilities(): HasMany
    {
        return $this->hasMany(SystemAbility::class, 'module_id');
    }

    /**
     * The licenses function
     *
     * @return HasMany
     */
    public function licenses(): HasMany
    {
        return $this->hasMany(SystemLicense::class, 'module_id');
    }

    /**
     * The pages function
     *
     * @return HasMany
     */
    public function pages(): HasMany
    {
        return $this->hasMany(SystemPage::class, 'module_id');
    }

    /**
     * The permissions function
     *
     * @return HasMany
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(SystemPermission::class, 'module_id');
    }

    /**
     * pageTitle function
     *
     * @param [type] $slug
     * @return string
     */
    public function pageTitle($slug): string | null
    {
        return Cache::flexible('title_of_' . $slug, [60, 3600], function () use ($slug) {
            return optional($this->pages()->where('slug', $slug)->first())->title;
        });
    }

    /**
     * The model store method
     *
     * @param Request $request
     * @return void
     */
    public static function storeRecord(Request $request)
    {
        $model = new static();

        DB::connection($model->connection)->beginTransaction();

        try {
            // $model->save();

            DB::connection($model->connection)->commit();

            // return new ModuleResource($model);
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
            $model->name = $request->name;
            $model->icon = $request->icon;
            $model->color = $request->color;
            $model->highlight = $request->highlight;
            $model->type = $request->type;
            $model->domain = $request->domain;
            $model->prefix = $request->prefix;
            $model->color = $request->color;
            $model->highlight = $request->highlight;
            $model->git_address = $request->git_address;
            $model->meta = $request->meta;
            $model->desktop = $request->desktop;
            $model->mobile = $request->mobile;
            $model->enabled = $request->enabled;
            $model->published = $request->published;
            $model->save();

            DB::connection($model->connection)->commit();

            return new ModuleResource($model);
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
