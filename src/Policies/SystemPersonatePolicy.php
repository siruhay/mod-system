<?php

namespace Module\System\Policies;

use Module\System\Models\SystemUser;
use Module\System\Models\SystemPersonate;
use Illuminate\Auth\Access\Response;

class SystemPersonatePolicy
{
    /**
    * Perform pre-authorization checks.
    */
    public function before(SystemUser $user, string $ability): bool|null
    {
        if ($user->hasAbility('system-superadmin')) {
            return true;
        }
    
        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function view(SystemUser $user): bool
    {
        return $user->hasPermission('view-system-personate');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function show(SystemUser $user, SystemPersonate $systemPersonate): bool
    {
        return $user->hasPermission('show-system-personate');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(SystemUser $user): bool
    {
        return $user->hasPermission('create-system-personate');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(SystemUser $user, SystemPersonate $systemPersonate): bool
    {
        return $user->hasPermission('update-system-personate');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(SystemUser $user, SystemPersonate $systemPersonate): bool
    {
        return $user->hasPermission('delete-system-personate');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(SystemUser $user, SystemPersonate $systemPersonate): bool
    {
        return $user->hasPermission('restore-system-personate');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function destroy(SystemUser $user, SystemPersonate $systemPersonate): bool
    {
        return $user->hasPermission('destroy-system-personate');
    }
}
