<?php

namespace Module\System\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\System\Models\SystemPoll;
use Module\System\Http\Resources\PollCollection;
use Module\System\Http\Resources\PollShowResource;

class SystemPollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('view', SystemPoll::class);

        return new PollCollection(
            SystemPoll::applyMode($request->trashed)
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
        Gate::authorize('create', SystemPoll::class);

        $request->validate([]);

        return SystemPoll::storeRecord($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\System\Models\SystemPoll $systemPoll
     * @return \Illuminate\Http\Response
     */
    public function show(SystemPoll $systemPoll)
    {
        Gate::authorize('show', $systemPoll);

        return new PollShowResource($systemPoll);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\System\Models\SystemPoll $systemPoll
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemPoll $systemPoll)
    {
        Gate::authorize('update', $systemPoll);

        $request->validate([]);

        return SystemPoll::updateRecord($request, $systemPoll);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\System\Models\SystemPoll $systemPoll
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemPoll $systemPoll)
    {
        Gate::authorize('delete', $systemPoll);

        return SystemPoll::deleteRecord($systemPoll);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemPoll $systemPoll
     * @return \Illuminate\Http\Response
     */
    public function restore(SystemPoll $systemPoll)
    {
        Gate::authorize('restore', $systemPoll);

        return SystemPoll::restoreRecord($systemPoll);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemPoll $systemPoll
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(SystemPoll $systemPoll)
    {
        Gate::authorize('destroy', $systemPoll);

        return SystemPoll::destroyRecord($systemPoll);
    }
}
