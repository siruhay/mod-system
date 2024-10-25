<?php

namespace Module\System\Policies;

use Module\System\Models\SystemUser;
use Module\System\Models\SystemApproval;
use Illuminate\Auth\Access\Response;

class SystemApprovalPolicy
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
        return $user->hasPermission('view-system-approval');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function show(SystemUser $user, SystemApproval $systemApproval): bool
    {
        return $user->hasPermission('show-system-approval');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(SystemUser $user): bool
    {
        return $user->hasPermission('create-system-approval');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(SystemUser $user, SystemApproval $systemApproval): bool
    {
        return $user->hasPermission('update-system-approval');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(SystemUser $user, SystemApproval $systemApproval): bool
    {
        return $user->hasPermission('delete-system-approval');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(SystemUser $user, SystemApproval $systemApproval): bool
    {
        return $user->hasPermission('restore-system-approval');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function destroy(SystemUser $user, SystemApproval $systemApproval): bool
    {
        return $user->hasPermission('destroy-system-approval');
    }
}
