<?php

use Illuminate\Support\Facades\Route;
use Module\System\Http\Controllers\DashboardController;
use Module\System\Http\Controllers\SystemPageController;
use Module\System\Http\Controllers\SystemRoleController;
use Module\System\Http\Controllers\SystemUserController;
use Module\System\Http\Controllers\SystemModuleController;
use Module\System\Http\Controllers\SystemAbilityController;
use Module\System\Http\Controllers\SystemAuditorController;
use Module\System\Http\Controllers\SystemOperatorController;
use Module\System\Http\Controllers\SystemThirdPartyController;
use Module\System\Http\Controllers\SystemAbilityPageController;
use Module\System\Http\Controllers\SystemAbilityLicenseController;
use Module\System\Http\Controllers\SystemPagePermissionController;

Route::get('dashboard', [DashboardController::class, 'systemIndex']);

Route::resource('auditor', SystemAuditorController::class)->parameters(['auditor' => 'systemAuditor']);

Route::delete('module/{systemModule}/force', [SystemModuleController::class, 'forceDelete']);
Route::put('module/{systemModule}/restore', [SystemModuleController::class, 'restore']);
Route::get('module/{systemModule}/check-for-update', [SystemModuleController::class, 'checkForUpdate']);
Route::post('module/{systemModule}/process-update', [SystemModuleController::class, 'processUpdate']);
Route::resource('module', SystemModuleController::class)->parameters(['module' => 'systemModule']);

Route::resource('module.ability', SystemAbilityController::class)->parameters(['module' => 'systemModule', 'ability' => 'systemAbility']);

Route::delete('ability/{systemAbility}/page/{systemAbilityPage}/force', [SystemAbilityPageController::class, 'forceDelete']);
Route::put('ability/{systemAbility}/page/{systemAbilityPage}/restore', [SystemAbilityPageController::class, 'restore']);
Route::resource('ability.page', SystemAbilityPageController::class)->parameters(['ability' => 'systemAbility', 'page' => 'systemAbilityPage']);

Route::resource('ability.license', SystemAbilityLicenseController::class)->parameters(['ability' => 'systemAbility', 'license' => 'systemLicense']);

Route::resource('module.page', SystemPageController::class)->parameters(['module' => 'systemModule', 'page' => 'systemPage']);
Route::resource('page.permission', SystemPagePermissionController::class)->parameters(['page' => 'systemPage', 'permission' => 'systemPermission']);

Route::resource('operator', SystemOperatorController::class)->parameters(['operator' => 'systemOperator']);

Route::delete('role/{systemRole}/force', [SystemRoleController::class, 'forceDelete']);
Route::put('role/{systemRole}/restore', [SystemRoleController::class, 'restore']);
Route::resource('role', SystemRoleController::class)->parameters(['role' => 'systemRole']);

Route::get('user/search', [SystemUserController::class, 'search']);
Route::post('user/grant-permissions', [SystemUserController::class, 'grantPermissions']);
Route::resource('user', SystemUserController::class)->parameters(['user' => 'systemUser']);

Route::resource('thirdparty', SystemThirdPartyController::class)->parameters(['thirdparty' => 'systemThirdParty']);