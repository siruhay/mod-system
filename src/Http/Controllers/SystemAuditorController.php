<?php

namespace Module\System\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\System\Models\SystemAuditor;
use Module\System\Http\Resources\AuditorCollection;
use Module\System\Http\Resources\AuditorShowResource;

class SystemAuditorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('view', SystemAuditor::class);

        return new AuditorCollection(
            SystemAuditor::applyMode($request->trashed)
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
        Gate::authorize('create', SystemAuditor::class);

        $request->validate([]);

        return SystemAuditor::storeRecord($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\System\Models\SystemAuditor $systemAuditor
     * @return \Illuminate\Http\Response
     */
    public function show(SystemAuditor $systemAuditor)
    {
        Gate::authorize('show', $systemAuditor);

        return new AuditorShowResource($systemAuditor);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\System\Models\SystemAuditor $systemAuditor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemAuditor $systemAuditor)
    {
        Gate::authorize('update', $systemAuditor);

        $request->validate([]);

        return SystemAuditor::updateRecord($request, $systemAuditor);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\System\Models\SystemAuditor $systemAuditor
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemAuditor $systemAuditor)
    {
        Gate::authorize('delete', $systemAuditor);

        return SystemAuditor::deleteRecord($systemAuditor);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemAuditor $systemAuditor
     * @return \Illuminate\Http\Response
     */
    public function restore(SystemAuditor $systemAuditor)
    {
        Gate::authorize('restore', $systemAuditor);

        return SystemAuditor::restoreRecord($systemAuditor);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemAuditor $systemAuditor
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(SystemAuditor $systemAuditor)
    {
        Gate::authorize('destroy', $systemAuditor);

        return SystemAuditor::destroyRecord($systemAuditor);
    }
}
