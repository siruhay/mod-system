<?php

namespace Module\System\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\System\Models\SystemVote;
use Module\System\Http\Resources\VoteCollection;
use Module\System\Http\Resources\VoteShowResource;

class SystemVoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('view', SystemVote::class);

        return new VoteCollection(
            SystemVote::applyMode($request->trashed)
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
        Gate::authorize('create', SystemVote::class);

        $request->validate([]);

        return SystemVote::storeRecord($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\System\Models\SystemVote $systemVote
     * @return \Illuminate\Http\Response
     */
    public function show(SystemVote $systemVote)
    {
        Gate::authorize('show', $systemVote);

        return new VoteShowResource($systemVote);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\System\Models\SystemVote $systemVote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemVote $systemVote)
    {
        Gate::authorize('update', $systemVote);

        $request->validate([]);

        return SystemVote::updateRecord($request, $systemVote);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\System\Models\SystemVote $systemVote
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemVote $systemVote)
    {
        Gate::authorize('delete', $systemVote);

        return SystemVote::deleteRecord($systemVote);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemVote $systemVote
     * @return \Illuminate\Http\Response
     */
    public function restore(SystemVote $systemVote)
    {
        Gate::authorize('restore', $systemVote);

        return SystemVote::restoreRecord($systemVote);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemVote $systemVote
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(SystemVote $systemVote)
    {
        Gate::authorize('destroy', $systemVote);

        return SystemVote::destroyRecord($systemVote);
    }
}
