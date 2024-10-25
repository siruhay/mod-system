<?php

namespace Module\System\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\System\Models\SystemApproval;
use Module\System\Http\Resources\ApprovalCollection;
use Module\System\Http\Resources\ApprovalShowResource;

class SystemApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('view', SystemApproval::class);

        return new ApprovalCollection(
            SystemApproval::applyMode($request->mode)
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
        Gate::authorize('create', SystemApproval::class);

        $request->validate([]);

        return SystemApproval::storeRecord($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\System\Models\SystemApproval $systemApproval
     * @return \Illuminate\Http\Response
     */
    public function show(SystemApproval $systemApproval)
    {
        Gate::authorize('show', $systemApproval);

        return new ApprovalShowResource($systemApproval);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\System\Models\SystemApproval $systemApproval
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemApproval $systemApproval)
    {
        Gate::authorize('update', $systemApproval);

        $request->validate([]);

        return SystemApproval::updateRecord($request, $systemApproval);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\System\Models\SystemApproval $systemApproval
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemApproval $systemApproval)
    {
        Gate::authorize('delete', $systemApproval);

        return SystemApproval::deleteRecord($systemApproval);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemApproval $systemApproval
     * @return \Illuminate\Http\Response
     */
    public function restore(SystemApproval $systemApproval)
    {
        Gate::authorize('restore', $systemApproval);

        return SystemApproval::restoreRecord($systemApproval);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemApproval $systemApproval
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(SystemApproval $systemApproval)
    {
        Gate::authorize('destroy', $systemApproval);

        return SystemApproval::destroyRecord($systemApproval);
    }
}
