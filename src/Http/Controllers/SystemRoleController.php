<?php

namespace Module\System\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\System\Models\SystemRole;
use Module\System\Http\Resources\RoleCollection;
use Module\System\Http\Resources\RoleShowResource;

class SystemRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('view', SystemRole::class);

        return new RoleCollection(
            SystemRole::applyMode($request->trashed)
                ->filter($request->filters)
                ->search($request->findBy)
                ->sortBy($request->sortBy)
                ->paginate($request->itemsPerPage)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Gate::authorize('create', SystemRole::class);

        $request->validate([]);

        return SystemRole::storeRecord($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\System\Models\SystemRole $systemRole
     * @return \Illuminate\Http\Response
     */
    public function show(SystemRole $systemRole)
    {
        Gate::authorize('show', $systemRole);

        return new RoleShowResource($systemRole);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\System\Models\SystemRole $systemRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemRole $systemRole)
    {
        Gate::authorize('update', $systemRole);

        $request->validate([]);

        return SystemRole::updateRecord($request, $systemRole);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\System\Models\SystemRole $systemRole
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemRole $systemRole)
    {
        Gate::authorize('delete', $systemRole);

        return SystemRole::deleteRecord($systemRole);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemRole $systemRole
     * @return \Illuminate\Http\Response
     */
    public function restore(SystemRole $systemRole)
    {
        Gate::authorize('restore', $systemRole);

        return SystemRole::restoreRecord($systemRole);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemRole $systemRole
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(SystemRole $systemRole)
    {
        Gate::authorize('destroy', $systemRole);

        return SystemRole::destroyRecord($systemRole);
    }
}
