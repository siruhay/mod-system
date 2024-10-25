<?php

namespace Module\System\Policies;

use Module\System\Models\SystemUser;
use Module\System\Models\SystemAbilityPage;
use Illuminate\Auth\Access\Response;

class SystemAbilityPagePolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(SystemUser $user, string $ability): bool|null
    {
        if ($user->hasLicenseAs('system-superadmin')) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function view(SystemUser $user): bool
    {
        return $user->hasPermission('view-system-abilitypage');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function show(SystemUser $user, SystemAbilityPage $systemAbilityPage): bool
    {
        return $user->hasPermission('show-system-abilitypage');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(SystemUser $user): bool
    {
        return $user->hasPermission('create-system-abilitypage');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(SystemUser $user, SystemAbilityPage $systemAbilityPage): bool
    {
        return $user->hasPermission('update-system-abilitypage');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(SystemUser $user, SystemAbilityPage $systemAbilityPage): bool
    {
        return $user->hasPermission('delete-system-abilitypage');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(SystemUser $user, SystemAbilityPage $systemAbilityPage): bool
    {
        return $user->hasPermission('restore-system-abilitypage');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function destroy(SystemUser $user, SystemAbilityPage $systemAbilityPage): bool
    {
        return $user->hasPermission('destroy-system-abilitypage');
    }
}
