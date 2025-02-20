<?php

namespace Module\System\Models;

use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use Module\System\Traits\CanLike;
use Module\System\Traits\CanRate;
use Module\System\Traits\CanVote;
use Module\System\Traits\HasMeta;
use Illuminate\Support\Facades\DB;
use Module\System\Traits\Auditable;
use Illuminate\Support\Facades\Hash;
use Module\System\Traits\Filterable;
use Module\System\Traits\Notifiable;
use Module\System\Traits\Searchable;
use Illuminate\Support\Facades\Cache;
use Module\System\Traits\Impersonate;
use Module\System\Traits\HasPageSetup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class SystemUser extends Authenticatable
{
    use Auditable;
    use CanLike;
    use CanRate;
    use CanVote;
    use Filterable;
    use HasApiTokens;
    use HasMeta;
    use HasPageSetup;
    use Impersonate;
    use Notifiable;
    use Searchable;
    use SoftDeletes;
    use TwoFactorAuthenticatable;

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
    protected $table = 'system_users';

    /**
     * The roles variable
     *
     * @var array
     */
    protected $roles = ['system-user'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The default key for the order.
     *
     * @var string
     */
    protected $defaultOrder = 'name';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'meta' => 'array',
            'password' => 'hashed',
        ];
    }

    /**
     * The authenticatedShowResource function
     *
     * @param Request $request
     * @param [type] $model
     * @return array
     */
    public static function authenticatedShowResource(Request $request, $model): array
    {
        $modules = $model->getModules();

        $checksum = md5(json_encode($modules));

        return array_merge(
            [
                'auth' => [
                    'gender' => $model->gender,
                    'username' => $model->name,
                    'usermail' => $model->email,
                    'secured' => $model->secured,
                    'avatar' => $model->avatar ? route('photo', ['path' => $model->avatar]) : null,
                    'highlight' => $model->highlight,
                    'theme' => $model->theme,
                ],

                'checksum' => $checksum,

                'modules' => $modules,
            ],

            $request->mobile ? [
                'token' => $model->generateToken()
            ] : [],

            $request->session()->has('impersonate_source_id') ? [
                'impersonated' => true
            ] : []
        );
    }

    /**
     * The userable function
     *
     * @return MorphTo
     */
    public function userable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * The abilities function
     *
     * @return HasManyThrough
     */
    public function abilities(): HasManyThrough
    {
        return $this->hasManyThrough(
            SystemAbility::class,
            SystemLicense::class,
            'ability_id',
            'id',
        );
    }

    /**
     * The licenses function
     *
     * @return HasMany
     */
    public function licenses(): HasMany
    {
        return $this->hasMany(SystemLicense::class, 'user_id');
    }

    /**
     * The modules function
     *
     * @return HasManyThrough
     */
    public function modules(): HasManyThrough
    {
        return $this->hasManyThrough(
            SystemModule::class,
            SystemLicense::class,
            'module_id',
            'user_id'
        );
    }

    /**
     * The getLicensePages function
     *
     * @param \Module\System\Models\SystemAbility $license
     * @return array
     */
    private function getLicensePages(SystemAbility $ability): array
    {
        $pages = with($ability->pages)->reduce(function ($carry, $abilityPage) {
            $page = optional($abilityPage)->page;

            if (optional($page)->enabled) {
                array_push($carry, [
                    'name' => $page->name,
                    'title' => $page->title,
                    'slug' => $page->slug,
                    'icon' => $page->icon,
                    'path' => $page->path,
                    'side' => (bool) $page->side,
                    'dock' => (bool) $page->dock,
                    'parent' => $page->parent_id === null,
                    'parent_path' => optional($page->parent)->slug,
                    'enabled' => (bool) $page->enabled,
                    'order' => $page->_lft
                ]);
            }

            return $carry;
        }, []);

        usort($pages, function ($a, $b) {
            return $a['order'] - $b['order'];
        });

        return $pages;
    }

    /**
     * The generateToken function
     *
     * @return void
     */
    public function generateToken()
    {
        if (!$userToken = $this->tokens()->first()) {
            return with(static::createToken($this->name))->plainTextToken;
        }

        $plainTextToken = $this->generateTokenString();
        $userToken->token = hash('sha256', $plainTextToken);
        $userToken->save();

        return $userToken->getKey() . '|' . $plainTextToken;
    }

    /**
     * The getModules function
     *
     * @return array
     */
    public function getModules(): array
    {
        return Cache::flexible($this->id . '-modules', [60, 3600], function () {
            $modules = with($this->licenses()->with([
                'ability',
                'ability.pages',
                'ability.pages.page',
                'ability.pages.page.parent',
                'module'
            ])->get())->reduce(function ($carry, $license) {
                $module = optional($license)->module;

                if ((optional($module)->enabled && optional($module)->published) || (optional($module)->enabled && !optional($module)->published && $this->debuger)) {
                    array_push($carry[$module->type], [
                        'name' => $module->name,
                        'slug' => $module->slug,
                        'icon' => $module->icon,
                        'color' => $module->color,
                        'highlight' => $module->highlight,
                        'domain' => $module->domain,
                        'prefix' => $module->prefix,
                        'desktop' => (bool) $module->desktop,
                        'mobile' => (bool) $module->mobile,
                        'order' => $module->_lft,
                        'pages' => $this->getLicensePages($license->ability)
                    ]);
                }

                return $carry;
            }, [
                'account' => [],
                'administrator' => [],
                'personal' => [],
            ]);

            usort($modules['administrator'], function ($a, $b) {
                return $a['order'] - $b['order'];
            });

            usort($modules['personal'], function ($a, $b) {
                return $a['order'] - $b['order'];
            });

            return $modules;
        });
    }

    /**
     * The getUserAbilities function
     *
     * @return array
     */
    protected function getUserAbilities(): array
    {
        return Cache::flexible($this->id . '-abilities', [60, 3600], function () {
            return $this->licenses()->pluck('name')->toArray();
        });
    }

    /**
     * The addLicense function
     *
     * @param string $name
     * @return void
     */
    public function addLicense(string $name): bool | SystemLicense
    {
        $ability = SystemAbility::firstWhere('name', $name);

        if (!$ability) {
            return false;
        }

        if (!$model = SystemLicense::where('user_id', $this->id)->where('name', $ability->name)->first()) {
            $model = new SystemLicense();
        }

        $model->name = $ability->name;
        $model->module_id = $ability->module_id;
        $model->ability_id = $ability->id;

        return $this->licenses()->save($model);
    }

    /**
     * The hasLicenseAs function
     *
     * @param string $slug
     * @return boolean
     */
    public function hasLicenseAs(string $slug): bool
    {
        return in_array($slug, $this->getUserAbilities(), true);
    }

    /**
     * The hasAnyLicense function
     *
     * @param array<int, string> $permissions
     * @return boolean
     */
    public function hasAnyLicense(...$abilities): bool
    {
        foreach ($abilities as $ability) {
            if (in_array($ability, $this->getUserAbilities(), true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * The getUserPermissions function
     *
     * @return array
     */
    protected function getUserPermissions(): array
    {
        return Cache::flexible($this->id . '-permissions', [60, 3600], function () {
            $results = [];

            foreach ($this->licenses()->with(['ability', 'ability.permissions'])->get() as $license) {
                $results = array_merge($results, $license->ability->permissions()->pluck('name')->toArray());
            }

            return $results;
        });
    }

    /**
     * The hasPermission function
     *
     * @param string $slug
     * @return boolean
     */
    public function hasPermission(string $slug): bool
    {
        return in_array($slug, $this->getUserPermissions(), true);
    }

    /**
     * The hasAnyPermission function
     *
     * @param array<int, string> $permissions
     * @return boolean
     */
    public function hasAnyPermission(...$permissions): bool
    {
        foreach ($permissions as $permission) {
            if (in_array($permission, $this->getUserPermissions(), true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * updateProfile function
     *
     * @param Request $request
     * @param SystemUser $user
     * @return void
     */
    public function updateProfile(Request $request, SystemUser $user)
    {
        try {
            $user->theme = $request->theme;
            $user->highlight = $request->highlight;
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Update profile berhasil.',
                'auth' => [
                    'avatar' => $user->avatar,
                    'gender' => $user->gender,
                    'highlight' => $user->highlight,
                    'secured' => $user->secured,
                    'theme' => $user->theme,
                    'usermail' => $user->email,
                    'username' => $user->name,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * updatePassword function
     *
     * @return void
     */
    public function updatePassword(Request $request, SystemUser $user)
    {
        try {
            $user->forceFill([
                'password' => Hash::make($request->password),
                'password_updated_at' => now()
            ])->save();

            DB::table(env('SESSION_TABLE'))
                ->where('user_id', $request->user()->getAuthIdentifier())
                ->whereNot('id', $request->session()->getId())
                ->delete();

            return response()->json([
                'status' => true,
                'message' => 'Update password berhasil.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * createUserFromEvent function
     *
     * @param Model $model
     * @return void
     */
    public static function createUserFromEvent(Model $source)
    {
        try {
            $model = new static;
            $model->name = $source->name;
            $model->email = $source->slug;
            $model->password = Hash::make(env('DEFAULT_PASSWORD', 'SiruhayMantab'));

            $source->user()->save($model);

            return $model;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * updateAbility function
     *
     * @param [type] $model
     * @return void
     */
    public static function updateAbility($model, $source)
    {
        if (!$model) {
            return;
        }

        $rolemode = null;

        if ($source->position_id) {
            switch ($source->position_id) {
                case 19:
                    # KETUA | chairman
                    $rolemode = 'chairman';
                    break;

                default:
                    # member
                    $rolemode = 'member';
                    break;
            }
        }

        if ($source->type) {
            switch ($source->type) {
                case 'CHAIRMAN':
                    # KETUA | chairman
                    $rolemode = 'committee-chairman';
                    break;

                case 'SPEAKER':
                    # KETUA | chairman
                    $rolemode = 'speaker';
                    break;

                default:
                    # member
                    $rolemode = 'committee-member';
                    break;
            }
        }


        if ($role = SystemRole::with(['abilities'])->firstWhere('slug', $rolemode)) {
            foreach ($role->abilities as $ability) {
                if (!$model->hasLicenseAs($ability->name)) {
                    $model->addLicense($ability->name);
                }
            }
        }

        Artisan::call('cache:clear');
    }

    /**
     * updateUserAvatar function
     *
     * @param $model
     * @param string $filepath
     * @return void
     */
    public static function updateUserAvatar($model, $filepath)
    {
        if (!$model || !$filepath) {
            return;
        }

        $model->avatar = $filepath;
        $model->save();
    }
}
