<?php

namespace Module\System\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\System\Models\SystemTasklist;
use Module\System\Http\Resources\TasklistCollection;
use Module\System\Http\Resources\TasklistShowResource;

class SystemTasklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('view', SystemTasklist::class);

        return new TasklistCollection(
            SystemTasklist::applyMode($request->mode)
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
        Gate::authorize('create', SystemTasklist::class);

        $request->validate([]);

        return SystemTasklist::storeRecord($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\System\Models\SystemTasklist $systemTasklist
     * @return \Illuminate\Http\Response
     */
    public function show(SystemTasklist $systemTasklist)
    {
        Gate::authorize('show', $systemTasklist);

        return new TasklistShowResource($systemTasklist);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\System\Models\SystemTasklist $systemTasklist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemTasklist $systemTasklist)
    {
        Gate::authorize('update', $systemTasklist);

        $request->validate([]);

        return SystemTasklist::updateRecord($request, $systemTasklist);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\System\Models\SystemTasklist $systemTasklist
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemTasklist $systemTasklist)
    {
        Gate::authorize('delete', $systemTasklist);

        return SystemTasklist::deleteRecord($systemTasklist);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemTasklist $systemTasklist
     * @return \Illuminate\Http\Response
     */
    public function restore(SystemTasklist $systemTasklist)
    {
        Gate::authorize('restore', $systemTasklist);

        return SystemTasklist::restoreRecord($systemTasklist);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemTasklist $systemTasklist
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(SystemTasklist $systemTasklist)
    {
        Gate::authorize('destroy', $systemTasklist);

        return SystemTasklist::destroyRecord($systemTasklist);
    }
}
