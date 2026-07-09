# Module: System

Path: `modules/system` · Package: `siruhay/mod-system` · Namespace: `Module\System` · Provider: `Module\System\Providers\SystemServiceProvider`

## 1. Overview

The `system` module is the **core/foundation module of the platform**. It is the largest module in the codebase and owns:

- Authentication, sessions, and 2FA (via Laravel Fortify + Sanctum)
- Users, roles, abilities, licenses, permissions, pages, and modules — i.e. the whole **RBAC / module-registry** system that drives what every other module can see and do
- Cross-cutting concerns reused by other modules: auditing, approvals, task lists, ratings/likes/votes, notifications, impersonation, filtering/searching
- The "Account" area (self-service profile/settings/dashboard for any logged-in user) and the "System" administrator area (manage modules, roles, users, permissions...)

Per `module.json`:
```json
{
    "name": "System",
    "namespace": "Module",
    "connection": "platform",
    "priority": 1,
    "providers": "Module\\System\\Providers\\SystemServiceProvider"
}
```
`priority: 1` and the dedicated `platform` DB connection (used by nearly every model, e.g. `SystemUser::$connection = 'platform'`) indicate this module boots first and holds the shared platform database, distinct from per-module databases.

`README.md`: "module untuk managemen module dalam platform" (module for managing modules within the platform).

## 2. Dependencies

From `composer.json` (`src/` autoloaded PSR-4 as `Module\System\`, plus `database/seeders` as `ModuleSystem\Seeders\`):

- `kalnoy/nestedset` — nested-set trees, used by `SystemModule` and `SystemPage` (menu/module hierarchy)
- `laravel/fortify` — auth scaffolding (login, 2FA, password reset), wired in `SystemServiceProvider`
- `league/flysystem-aws-s3-v3` — S3 file storage driver
- `maatwebsite/excel` — Excel import/export, used by `src/Imports/*`
- `guzzlehttp/guzzle` — HTTP client

It also depends implicitly on a platform package `Monoland\Platform\DiscoverEvents` (used in `src/Providers/EventServiceProvider.php`), and is depended on **by** other modules — e.g. `src/Listeners/CheckUserUpdate.php` listens to `Module\Training\Events\TrainingMemberUpdated`, `TrainingOfficialUpdated`, `TrainingCommitteeUpdate` and `Module\Foundation\Events\TrainingOfficialUpdated`, syncing `SystemUser` records and licenses whenever those other modules change. This is the clearest evidence that `system` is the shared identity/permission backbone other modules integrate with by firing events.

## 3. Data model / schema (`database/migrations`)

All tables live on the `platform` connection. Key tables (chronological):

| Migration | Table | Purpose |
|---|---|---|
| `2024_05_01_033106_create_system_users.php` | `system_users` | Auth users. Columns: `name`, `email` (unique), `gender` enum, `emailgov`, `password`, 2FA fields, `avatar`, `theme`, `highlight`, `nullableMorphs('userable')` (polymorphic link to a domain "person" record from another module), `debuger`, `secured`, `last_geolocation` (jsonb), `meta` (jsonb), soft deletes |
| `..._create_system_password_reset_tokens.php` | `system_password_reset_tokens` | Fortify password resets |
| `..._create_system_sessions.php` | `system_sessions` | Session table (`SESSION_TABLE` referenced via `env()`) |
| `..._create_system_notifications.php` | `system_notifications` | Laravel database notifications |
| `..._create_system_cache.php` / `_cache_locks.php` | `system_cache*` | Cache driver tables |
| `..._create_system_jobs.php` / `_job_batches.php` / `_failded_jobs.php` | queue tables | Queue driver tables (note typo "failded_jobs") |
| `..._create_system_personal_access_tokens.php` | `system_personal_access_tokens` | Sanctum tokens, model `SystemPersonalAccessToken` |
| `2024_05_01_045142_create_system_roles.php` | `system_roles` | `name`, `slug` (unique), `meta` |
| `2024_05_01_045155_create_system_modules.php` | `system_modules` | The **module registry**: `name`, `slug`, `icon`, `color`, `highlight`, `type` enum (`account`/`administrator`/`personal`), `domain`, `prefix`, `nullableMorphs('ownerable')`, `git_address`, `git_version`, `check_for_update`, `desktop`, `mobile`, `enabled`, `published`, `nestedSet()` (parent/lft/rgt for ordering/hierarchy) |
| `2024_05_01_045207_create_system_pages.php` | `system_pages` | Menu pages: `name`, `slug`, `title`, `icon`, `path`, `side`, `dock`, `enabled`, FK `module_id`, `nestedSet()` |
| `2024_05_01_045214_create_system_permissions.php` | `system_permissions` | `name`, `slug`, FK `page_id`, FK `module_id` |
| `2024_05_01_045233_create_system_abilities.php` | `system_abilities` | Connects a `role_id` to a `module_id` — an "ability" is effectively a role's access grant to a module |
| `2024_05_01_045301_create_system_ability_pages.php` | `system_ability_pages` | Which pages an ability can see: FK `ability_id`, `module_id`, `page_id` |
| `2024_05_01_045308_create_system_ability_permissions.php` | `system_ability_permissions` | Which fine-grained permissions an ability/page combo grants: FK `ability_id`, `ability_page_id`, `module_id`, `page_id`, `permission_id` |
| `2024_05_01_051621_create_system_licenses.php` | `system_licenses` | Join table granting a `user_id` an `ability_id` within a `module_id`, unique on `(name, user_id)` — this is what `SystemUser::addLicense()` writes to |
| `2024_05_01_045442_create_system_operator.php` | `system_operators` | Links a user/role to `structural_id`/`workunit_id` (org-structure references from other modules) |
| `2024_05_01_081650_create_system_user_logs.php` | `system_user_logs` | Audit log, uses custom base migration `Module\System\Supports\AuditMigration`; string primary key, `event`, `module`, `morphs('auditable')`, `dirties`/`origins` (jsonb diffs), `user_id`, `impersonate` fields, `coords` |
| `2024_05_02_024339_create_system_auditors.php` | `system_auditors` | Auditor assignment records |
| `2024_05_02_025140_create_system_thirdparties.php` | `system_thirdparties` | Third-party API clients, FK `role_id`, used with `generateToken` |
| `2024_06_20_152320/152954_create_system_polls/votes.php` | `system_polls`, `system_votes` | Simple polling with `options` (jsonb), unique `(user_id, poll_id)` |
| `2024_08_22_114845_create_system_approvals.php` | `system_approvals` | Generic approval workflow: `nullableMorphs('approvalable')`, `state` enum (`pending/approved/repaired/rejected/submitted`), `source_data`/`origin_data` jsonb, `officer_id`, `rolled_back_at` — backs the `MustBeApproved` trait |
| `2024_08_22_130921_create_system_tasklists.php` | `system_tasklists` | Generic task list: `nullableMorphs('taskable')`, `state` enum — backs the `Taskable` trait |
| `2024_08_22_132613_create_system_personates.php` | `system_personates` | Delegation of authority ("personate"): `authorized_id`, `authorizer_id`, date range, `passphrase` |
| `2024_11_27_181245_create_system_ratings.php` | `system_ratings` | Generic like/rate/vote store: `morphs('model')`, `morphs('rateable')`, `value`, `type` — backs `CanLike`/`CanRate`/`CanVote`/`Likeable`/`Rateable`/`Votable` traits |

Seeders (`database/seeders/`): `DatabaseSeeder`, `SystemBaseSeeder`, `SystemDataSeeder`, `SystemUserSeeder`, `SystemTestSeeder`, backed by Excel master files in `database/masters/` (`base-seeder.xlsx`, `data-seeder.xlsx`, `accn-seeder.xlsx`) consumed through `src/Imports/*` (`AbilityImport`, `AbilityPageImport`, `AbilityPermissionImport`, `BaseImport`, `DataImport`, `ModuleImport`, `PageImport`, `PermissionImport`, `RoleImport`).

## 4. Domain entities / Models (`src/Models`)

All extend `Illuminate\Database\Eloquent\Model` (or `Authenticatable` for the user) on the `platform` connection, and lean heavily on the shared traits (section 7):

- **`SystemUser`** (`src/Models/SystemUser.php`) — the auth-able user. Uses `Auditable, CanLike, CanRate, CanVote, Filterable, HasApiTokens, HasMeta, HasPageSetup, Impersonate, Notifiable, Searchable, SoftDeletes, TwoFactorAuthenticatable`. Notable methods: `getModules()` (builds the full menu/module tree the frontend renders per user, based on active licenses), `getLicensePages()`, `hasLicenseAs()`, `hasAnyLicense()`, `hasPermission()`/`hasAnyPermission()` (cached via `Cache::flexible`), `addLicense()`, `generateToken()` (Sanctum), `updateProfile()`, `updatePassword()`, and static `createUserFromEvent()` / `updateAbility()` used by cross-module listeners to auto-provision users and grant default abilities based on org role/position.
- **`SystemRole`** — `name`/`slug`; `hasMany(SystemAbility)`; static CRUD helpers `storeRecord/updateRecord/deleteRecord/restoreRecord/destroyRecord` wrapped in DB transactions returning `RoleResource`.
- **`SystemModule`** — uses `NodeTrait` (nested set) for module hierarchy; `hasMany` on `abilities`, `licenses`, `pages`, `permissions`; `pageTitle($slug)` cached lookup; same static CRUD-transaction pattern as `SystemRole`.
- **`SystemPage`**, **`SystemPermission`**, **`SystemAbility`**, **`SystemAbilityPage`**, **`SystemAbilityPermission`**, **`SystemLicense`** — the RBAC graph described in section 3.
- **`SystemOperator`**, **`SystemThirdParty`** — auxiliary account types.
- **`SystemAuditor`**, **`SystemUserLog`** (has static `eventLog()` used by the `Auditable` trait) — audit trail.
- **`SystemApproval`**, **`SystemTasklist`**, **`SystemPersonate`** — generic workflow/approval/delegation entities.
- **`SystemPoll`**, **`SystemVote`**, **`SystemRating`** — engagement/generic rating primitives.
- **`SystemNotification`**, **`SystemPersonalAccessToken`** — framework support models (notifications table model, Sanctum token model bound in `SystemServiceProvider::boot()`).

## 5. HTTP layer

### Controllers (`src/Http/Controllers`)
One controller per resource, following Laravel resource-controller conventions, e.g. `SystemUserController`, `SystemRoleController`, `SystemModuleController`, `SystemAbilityController`, `SystemAbilityPageController`, `SystemAbilityLicenseController`, `SystemPageController`, `SystemPagePermissionController`, `SystemOperatorController`, `SystemAuditorController`, `SystemThirdPartyController`, `SystemPollController`, `SystemVoteController`, `SystemRatingController`, `SystemApprovalController`, `SystemTasklistController`, `SystemUserLogController`. Plus auth/account-specific controllers: `AuthenticatedSessionController`, `TwoFactorAuthenticationController`, `SystemPersonateController`, `DashboardController`.

### Resources (`src/Http/Resources`)
Each entity ships a `*Collection`, `*Resource`, and `*ShowResource` triad (e.g. `RoleCollection`/`RoleResource`/`RoleShowResource`) for list/item/detail JSON shapes.

### Routes (`routes/`), wired by `src/Providers/RouteServiceProvider.php`
- `mapWebRoutes()`:
  - `account-web.php` mounted at prefix `account`, middleware `web` — currently only auth POST endpoints (`/login`, `/login-challenge`, `/login-finder`, `/reset-password`) via `AuthenticatedSessionController`.
  - `system-web.php` mounted at a **dynamic prefix/domain** looked up at runtime from `system_modules` where `slug = 'system'` (cached with `Cache::flexible('system-domain'/'system-prefix', [60, 3600])`) — currently empty (`<?php` only), reserved for future web views.
- `mapApiRoutes()`:
  - `account-api.php` mounted at `account/api`, middleware `['api', 'auth:sanctum']`. Key routes: `GET dashboard`, `GET setting`, `POST setting/confirmed-factor-authentication`, `POST setting/update-password`, `POST setting/update-profile`, `POST setting/two-factor-authentication`, `DELETE setting/two-factor-authentication`, `GET setting/two-factor-qr-code`, `POST setting/logout-other-devices`, `POST impersonate-take/{sourceId}`, `POST impersonate-leave`, `POST user-geodata`, `GET user-data`, `GET user-modules`, `GET services`, `POST logout`, `Route::resource('activity', SystemUserLogController::class)`.
  - `system-api.php` mounted at the same dynamic domain/prefix + `/api`, middleware `['api', 'auth:sanctum']`. Key routes: `GET dashboard`, `Route::resource('auditor', ...)`, module CRUD plus `DELETE module/{systemModule}/force`, `PUT module/{systemModule}/restore`, `GET module/{systemModule}/check-for-update`, `POST module/{systemModule}/process-update`; nested `Route::resource('module.ability', ...)`; `Route::resource('ability.page', ...)` plus force-delete/restore; `Route::resource('ability.license', ...)`; `Route::resource('module.page', ...)`; `Route::resource('page.permission', ...)`; `Route::resource('operator', ...)`; role CRUD plus force-delete/restore; `Route::resource('user', ...)` plus `GET user/search` and `POST user/grant-permissions`; `Route::resource('thirdparty', ...)` plus `GET thirdparty/{systemThirdParty}/generate-token`.

Note both API groups resolve `system-domain`/`system-prefix` from the DB at boot, meaning the "system" module's own API can be served off a custom subdomain/prefix configured through its own `system_modules` row (self-referential bootstrap).

## 6. Services / Supports (`src/Supports`)

- `helpers.php` — global `module($moduleId = null)` helper: fetch a `SystemModule` by id or by slug.
- `AuditMigration.php` — base `Migration` subclass used by the `system_user_logs` migration.
- `LaravelRating.php` / `LaravelRatingFacade.php` — small rating/like/vote engine backing the `CanLike/CanRate/CanVote` and `Likeable/Rateable/Votable` traits, bound in `SystemServiceProvider::register()` as `app()->bind('laravelRating', ...)`.

Other service-like code:
- `src/Actions/Fortify/*` — Fortify contracts implementations: `CreateNewUser`, `ResetUserPassword`, `UpdateUserPassword`, `UpdateUserProfileInformation`, `PasswordValidationRules`. Wired in `SystemServiceProvider::boot()` via `Fortify::createUsersUsing()`, etc.
- `src/Imports/*` — Maatwebsite Excel importers for seeding (`BaseImport`, `DataImport`, `RoleImport`, `ModuleImport`, `PageImport`, `PermissionImport`, `AbilityImport`, `AbilityPageImport`, `AbilityPermissionImport`).
- `src/LaravelPulse/*` — `PulseExceptions`, `PulseSlowQueries`, `PulseUsage`: custom Laravel Pulse recorders/cards for observability.
- `src/Jobs/SystemGrantPermission.php` — queued job (`ShouldQueue`) that re-syncs a user's licenses and grants a default `account-administrator` license, then clears cache.

## 7. Policies / Permissions (`src/Policies`)

One policy per entity (e.g. `SystemUserPolicy`, `SystemRolePolicy`, `SystemModulePolicy`, `SystemAbilityPolicy`, `SystemAbilityPagePolicy`, `SystemLicensePolicy`, `SystemPagePolicy`, `SystemPermissionPolicy`, `SystemOperatorPolicy`, `SystemAuditorPolicy`, `SystemThirdPartyPolicy`, `SystemPollPolicy`, `SystemVotePolicy`, `SystemRatingPolicy`, `SystemApprovalPolicy`, `SystemTasklistPolicy`, `SystemPersonatePolicy`, `SystemUserLogPolicy`). Uniform shape, e.g. `SystemUserPolicy`:

```php
public function before(SystemUser $user, string $ability): bool|null
{
    if ($user->hasLicenseAs('system-superadmin')) {
        return true;
    }
    return null;
}

public function view(SystemUser $user): bool { return $user->hasPermission('view-system-user'); }
public function show(SystemUser $user, SystemUser $systemUser): bool { return $user->hasPermission('show-system-user'); }
// create/update/delete/restore/destroy follow the same `hasPermission('<verb>-<entity>')` convention
```

Policy resolution is customized in `SystemServiceProvider::boot()`:
```php
Gate::guessPolicyNamesUsing(function ($modelClass) {
    return str($modelClass)->before('\\Models\\')->toString() . '\\Policies\\' . str($modelClass)->after('\\Models\\')->toString() . 'Policy';
});
```
This lets any module's `Xxx\Models\Foo` model resolve to `Xxx\Policies\FooPolicy` automatically — a platform-wide convention, not just for `system`.

Permission checks flow: `SystemUser::hasPermission($slug)` / `hasAnyPermission(...)` read a cached list (`Cache::flexible($id.'-permissions', ...)`) built from `licenses()->ability->permissions`. `hasLicenseAs($slug)` / `hasAnyLicense(...)` check the cached `licenses` name list. Permission slugs follow `'<verb>-<entity>'` (e.g. `view-system-user`, `create-system-role`).

## 8. Events / Listeners / Jobs

- No `src/Events` directory in this module (it *consumes* events raised by other modules); `src/Providers/EventServiceProvider.php` auto-discovers listeners in `src/Listeners` via `Monoland\Platform\DiscoverEvents` and merges them into the app's listener map.
- `src/Listeners/CheckUserUpdate.php` — subscriber (`subscribe(Dispatcher $events)`) handling `Module\Training\Events\TrainingMemberUpdated`, `Module\Foundation\Events\TrainingOfficialUpdated`, `Module\Training\Events\TrainingCommitteeUpdate`, `Module\Training\Events\TrainingSettingUpdate`. Each handler: find-or-create a `SystemUser` via `SystemUser::createUserFromEvent()`, grant each ability in the event payload via `addLicense()`, ensure `account-administrator` license, then `Artisan::call('cache:clear')`. This is the primary integration point where other modules push identity/permission changes into `system`.
- `src/Jobs/SystemGrantPermission.php` — queued equivalent of the same re-sync logic, dispatched with a `userId`.
- `src/Notifications/UserLogged.php` — a Laravel notification (login/activity notice).

## 9. Frontend structure

Two separate Vue front-end areas ship inside this backend module (SPA pages consumed by the platform's frontend build, referenced via `@modules/system/...` alias):

- **`frontend/`** — the **System administration** SPA area, mounted at `/system` (see `frontend/router/index.js`, default export is a single route object with `path: "/system"`, `component: Base.vue`, and nested children). Pages under `frontend/pages/`: `dashboard`, `module`, `module-ability`, `module-ability-license`, `module-ability-page`, `module-page`, `module-page-permission`, `report`, `role`, `thirdparty`, `user` — each typically has `index.vue` plus a `crud/{data,create,edit,show}.vue` subset, mirroring the resource routes in `system-api.php`. `frontend/plugins/index.js` registers module-level Vue plugins.
- **`account/`** — the **Account (self-service)** SPA area, mounted at `/` (welcome) and `/account` (see `account/router/index.js`, exports an array of routes). Pages under `account/pages/`: `welcome`, `dashboard`, `service`, `activity` (+ `crud/data.vue`), `notification` (+ `crud/data.vue`), `setting`, plus a shared `Base.vue` layout — mirroring `account-api.php` endpoints (dashboard, settings, activity log, 2FA, impersonation).

Blade side (`resources/views/`, loaded in `SystemServiceProvider::boot()` via `loadViewsFrom(..., 'system')`): `welcome.blade.php`, `reports/css.blade.php` (used for print/PDF report styling, paired with `SystemAuditorController`/report resources).

## 10. `account/` and `excalidraw/` subfolders explained

- **`account/`** is *not* a separate module — it is the Vue SPA source for the self-service "My Account" area of the platform (profile, 2FA settings, notifications, activity log, service/module launcher), served under `/account` and backed by `routes/account-web.php` + `routes/account-api.php`. It sits alongside `frontend/` (the admin SPA) inside the same `system` module because both areas are owned by the same backend controllers/resources (`DashboardController`, `TwoFactorAuthenticationController`, `AuthenticatedSessionController`, `SystemUserLogController`).
- **`excalidraw/`** contains two [Excalidraw](https://excalidraw.com) diagram source files (raw JSON with `type`, `version`, `elements`, `appState` keys), **not application code**: `excalidraw/system.excalidraw` and `excalidraw/modular-monolith.excalidraw`. These are architecture/whiteboard diagrams (likely documenting the module's own domain model and the platform's overall modular-monolith architecture) that can be opened at excalidraw.com or in an Excalidraw-compatible editor/VS Code extension. They are documentation artifacts, not build inputs.

## 11. Notable patterns

- **Static CRUD-transaction methods on models**: `storeRecord`, `updateRecord`, `deleteRecord`, `restoreRecord`, `destroyRecord` are defined directly on models (e.g. `SystemRole`, `SystemModule`) rather than in dedicated service classes, each wrapping `DB::connection($model->connection)->beginTransaction()/commit()/rollBack()` and returning the paired `*Resource`.
- **Trait-driven cross-cutting behavior**: `Auditable` (auto audit log via `SystemUserLog::eventLog()` on model lifecycle hooks like `created`/`deleted`/`approved`/`confirmed`), `MustBeApproved` (auto-creates `SystemApproval` rows on `creating`/`updating`), `Taskable`, `Searchable`, `Filterable` (declarative filter/search config via `toFilterableArray()`/search attributes `SearchUsingFullText`/`SearchUsingPrefix`), `HasMeta` (schemaless `meta` jsonb accessor), `HasPageSetup` (frontend page/table metadata), `Impersonate`, `CanLike/CanRate/CanVote` + `Likeable/Rateable/Votable` (generic polymorphic engagement via `system_ratings`).
- **Cache::flexible for hot lookups**: permissions, licenses, module domain/prefix, and page titles are all cached with stale-while-revalidate style `Cache::flexible($key, [60, 3600], fn () => ...)`.
- **Self-referential module bootstrapping**: the system module's own routing prefix/domain is read from its own `system_modules` table row (slug `system`), same mechanism every other module presumably uses.
- **Nested sets** (`kalnoy/nestedset`) power ordering/hierarchy for both `system_modules` (module menu grouping) and `system_pages` (page/menu tree, with `parent_id`/`_lft` used for ordering in `SystemUser::getLicensePages()`).
- **Event-driven user provisioning**: other modules never create `SystemUser` records directly; they fire domain events (`TrainingMemberUpdated`, etc.) that `CheckUserUpdate` listens for for find-or-create + auto license grants — keeping `system` as the single source of truth for identity.

## 12. How to extend / integrate

- **New permission-guarded resource**: add a migration (platform connection), a Model with `Auditable`/`Filterable`/`Searchable`/`HasMeta`/`HasPageSetup` as needed, a Policy following the `hasPermission('<verb>-<slug>')` convention (relies on `Gate::guessPolicyNamesUsing` in `SystemServiceProvider`), Http Resources (`Collection`/`Resource`/`ShowResource`), a Controller, and register `Route::resource(...)` in `routes/system-api.php`.
- **Integrating another module with `system`'s identity/permissions**: fire a domain event from your module (see `Module\Training\Events\TrainingMemberUpdated` for the shape: exposes `model` with a `slug` and an `abilities` array) and either add a handler in `src/Listeners/CheckUserUpdate.php` or dispatch `Module\System\Jobs\SystemGrantPermission::dispatch($userId)` to resync licenses. Consume `module('<slug>')` helper (`src/Supports/helpers.php`) to fetch a `SystemModule` by slug from anywhere in the platform.
- **Frontend pages**: add Vue pages under `frontend/pages/<entity>/` (admin) or `account/pages/<area>/` (self-service) following the existing `index.vue` + `crud/{data,create,edit,show}.vue` convention, then register routes in `frontend/router/index.js` or `account/router/index.js` using the `@modules/system/...` webpack alias.
- **Checking access in code**: use `$user->hasPermission('slug')`, `$user->hasAnyPermission(...)`, `$user->hasLicenseAs('ability-name')`, `$user->hasAnyLicense(...)`, or standard Laravel `Gate`/`$this->authorize()` against the auto-resolved policy.
- **Diagrams**: update/open `excalidraw/system.excalidraw` and `excalidraw/modular-monolith.excalidraw` in Excalidraw when the domain model or module architecture changes, to keep the visual docs in sync.
