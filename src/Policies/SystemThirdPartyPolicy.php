<?php

namespace Module\System\Policies;

use Module\System\Models\SystemUser;
use Module\System\Models\SystemThirdParty;
use Illuminate\Auth\Access\Response;

class SystemThirdPartyPolicy
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
        return $user->hasPermission('view-system-thirdparty');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function show(SystemUser $user, SystemThirdParty $systemThirdParty): bool
    {
        return $user->hasPermission('show-system-thirdparty');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(SystemUser $user): bool
    {
        return $user->hasPermission('create-system-thirdparty');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(SystemUser $user, SystemThirdParty $systemThirdParty): bool
    {
        return $user->hasPermission('update-system-thirdparty');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(SystemUser $user, SystemThirdParty $systemThirdParty): bool
    {
        return $user->hasPermission('delete-system-thirdparty');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(SystemUser $user, SystemThirdParty $systemThirdParty): bool
    {
        return $user->hasPermission('restore-system-thirdparty');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function destroy(SystemUser $user, SystemThirdParty $systemThirdParty): bool
    {
        return $user->hasPermission('destroy-system-thirdparty');
    }
}
