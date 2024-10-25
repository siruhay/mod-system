<?php

namespace Module\System\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\System\Models\SystemPage;
use Module\System\Models\SystemPermission;
use Module\System\Http\Resources\PermissionCollection;
use Module\System\Http\Resources\PermissionShowResource;

class SystemPagePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Module\System\Models\SystemPage $systemPage
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, SystemPage $systemPage)
    {
        Gate::authorize('view', SystemPermission::class);

        return new PermissionCollection(
            $systemPage
                ->permissions()
                ->applyMode($request->trashed)
                ->filter($request->filters)
                ->search($request->findBy)
                ->sortBy($request->sortBy, $request->sortDesc)
                ->paginate($request->itemsPerPage)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\System\Models\SystemPage $systemPage
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, SystemPage $systemPage)
    {
        Gate::authorize('create', SystemPermission::class);

        $request->validate([]);

        return SystemPermission::storeRecord($request, $systemPage);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\System\Models\SystemPage $systemPage
     * @param  \Module\System\Models\SystemPermission $systemPermission
     * @return \Illuminate\Http\Response
     */
    public function show(SystemPage $systemPage, SystemPermission $systemPermission)
    {
        Gate::authorize('show', $systemPermission);

        return new PermissionShowResource($systemPermission);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\System\Models\SystemPage $systemPage
     * @param  \Module\System\Models\SystemPermission $systemPermission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemPage $systemPage, SystemPermission $systemPermission)
    {
        Gate::authorize('update', $systemPermission);

        $request->validate([]);

        return SystemPermission::updateRecord($request, $systemPermission);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\System\Models\SystemPage $systemPage
     * @param  \Module\System\Models\SystemPermission $systemPermission
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemPage $systemPage, SystemPermission $systemPermission)
    {
        Gate::authorize('delete', $systemPermission);

        return SystemPermission::deleteRecord($systemPermission);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemPermission $systemPermission
     * @return \Illuminate\Http\Response
     */
    public function restore(SystemPage $systemPage, SystemPermission $systemPermission)
    {
        Gate::authorize('restore', $systemPermission);

        return SystemPermission::restoreRecord($systemPermission);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemPermission $systemPermission
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(SystemPage $systemPage, SystemPermission $systemPermission)
    {
        Gate::authorize('destroy', $systemPermission);

        return SystemPermission::destroyRecord($systemPermission);
    }
}