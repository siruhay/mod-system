<?php

namespace Module\System\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\System\Models\SystemPersonate;
use Module\System\Http\Resources\PersonateCollection;
use Module\System\Http\Resources\PersonateShowResource;

class SystemPersonateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('view', SystemPersonate::class);

        return new PersonateCollection(
            SystemPersonate::applyMode($request->mode)
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
        Gate::authorize('create', SystemPersonate::class);

        $request->validate([]);

        return SystemPersonate::storeRecord($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\System\Models\SystemPersonate $systemPersonate
     * @return \Illuminate\Http\Response
     */
    public function show(SystemPersonate $systemPersonate)
    {
        Gate::authorize('show', $systemPersonate);

        return new PersonateShowResource($systemPersonate);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\System\Models\SystemPersonate $systemPersonate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemPersonate $systemPersonate)
    {
        Gate::authorize('update', $systemPersonate);

        $request->validate([]);

        return SystemPersonate::updateRecord($request, $systemPersonate);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\System\Models\SystemPersonate $systemPersonate
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemPersonate $systemPersonate)
    {
        Gate::authorize('delete', $systemPersonate);

        return SystemPersonate::deleteRecord($systemPersonate);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemPersonate $systemPersonate
     * @return \Illuminate\Http\Response
     */
    public function restore(SystemPersonate $systemPersonate)
    {
        Gate::authorize('restore', $systemPersonate);

        return SystemPersonate::restoreRecord($systemPersonate);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemPersonate $systemPersonate
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(SystemPersonate $systemPersonate)
    {
        Gate::authorize('destroy', $systemPersonate);

        return SystemPersonate::destroyRecord($systemPersonate);
    }
}
