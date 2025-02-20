<?php

namespace Module\System\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\System\Models\SystemThirdParty;
use Module\System\Http\Resources\ThirdPartyCollection;
use Module\System\Http\Resources\ThirdPartyShowResource;

class SystemThirdPartyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('view', SystemThirdParty::class);

        return new ThirdPartyCollection(
            SystemThirdParty::applyMode($request->mode)
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
        Gate::authorize('create', SystemThirdParty::class);

        $request->validate([]);

        return SystemThirdParty::storeRecord($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\System\Models\SystemThirdParty $systemThirdParty
     * @return \Illuminate\Http\Response
     */
    public function show(SystemThirdParty $systemThirdParty)
    {
        Gate::authorize('show', $systemThirdParty);

        return new ThirdPartyShowResource($systemThirdParty);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\System\Models\SystemThirdParty $systemThirdParty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemThirdParty $systemThirdParty)
    {
        Gate::authorize('update', $systemThirdParty);

        $request->validate([]);

        return SystemThirdParty::updateRecord($request, $systemThirdParty);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\System\Models\SystemThirdParty $systemThirdParty
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemThirdParty $systemThirdParty)
    {
        Gate::authorize('delete', $systemThirdParty);

        return SystemThirdParty::deleteRecord($systemThirdParty);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemThirdParty $systemThirdParty
     * @return \Illuminate\Http\Response
     */
    public function restore(SystemThirdParty $systemThirdParty)
    {
        Gate::authorize('restore', $systemThirdParty);

        return SystemThirdParty::restoreRecord($systemThirdParty);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemThirdParty $systemThirdParty
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(SystemThirdParty $systemThirdParty)
    {
        Gate::authorize('destroy', $systemThirdParty);

        return SystemThirdParty::destroyRecord($systemThirdParty);
    }
}
