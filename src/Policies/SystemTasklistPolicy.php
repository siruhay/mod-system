<?php

namespace Module\System\Policies;

use Module\System\Models\SystemUser;
use Module\System\Models\SystemTasklist;
use Illuminate\Auth\Access\Response;

class SystemTasklistPolicy
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
        return $user->hasPermission('view-system-tasklist');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function show(SystemUser $user, SystemTasklist $systemTasklist): bool
    {
        return $user->hasPermission('show-system-tasklist');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(SystemUser $user): bool
    {
        return $user->hasPermission('create-system-tasklist');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(SystemUser $user, SystemTasklist $systemTasklist): bool
    {
        return $user->hasPermission('update-system-tasklist');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(SystemUser $user, SystemTasklist $systemTasklist): bool
    {
        return $user->hasPermission('delete-system-tasklist');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(SystemUser $user, SystemTasklist $systemTasklist): bool
    {
        return $user->hasPermission('restore-system-tasklist');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function destroy(SystemUser $user, SystemTasklist $systemTasklist): bool
    {
        return $user->hasPermission('destroy-system-tasklist');
    }
}
