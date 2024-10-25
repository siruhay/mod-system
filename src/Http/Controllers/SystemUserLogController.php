<?php

namespace Module\System\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\System\Models\SystemUserLog;
use Module\System\Http\Resources\UserLogCollection;
use Module\System\Http\Resources\UserLogShowResource;

class SystemUserLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('view', SystemUserLog::class);

        return new UserLogCollection(
            SystemUserLog::with(['auditable'])
                ->forCurrentUser($request)
                ->applyMode($request->trashed)
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
        Gate::authorize('create', SystemUserLog::class);

        $request->validate([]);

        return SystemUserLog::storeRecord($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\System\Models\SystemUserLog $systemUserLog
     * @return \Illuminate\Http\Response
     */
    public function show(SystemUserLog $systemUserLog)
    {
        Gate::authorize('show', $systemUserLog);

        return new UserLogShowResource($systemUserLog);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\System\Models\SystemUserLog $systemUserLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemUserLog $systemUserLog)
    {
        Gate::authorize('update', $systemUserLog);

        $request->validate([]);

        return SystemUserLog::updateRecord($request, $systemUserLog);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\System\Models\SystemUserLog $systemUserLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemUserLog $systemUserLog)
    {
        Gate::authorize('delete', $systemUserLog);

        return SystemUserLog::deleteRecord($systemUserLog);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemUserLog $systemUserLog
     * @return \Illuminate\Http\Response
     */
    public function restore(SystemUserLog $systemUserLog)
    {
        Gate::authorize('restore', $systemUserLog);

        return SystemUserLog::restoreRecord($systemUserLog);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemUserLog $systemUserLog
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(SystemUserLog $systemUserLog)
    {
        Gate::authorize('destroy', $systemUserLog);

        return SystemUserLog::destroyRecord($systemUserLog);
    }
}
