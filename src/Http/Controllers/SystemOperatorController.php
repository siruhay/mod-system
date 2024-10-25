<?php

namespace Module\System\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\System\Models\SystemOperator;
use Module\System\Http\Resources\OperatorCollection;
use Module\System\Http\Resources\OperatorShowResource;

class SystemOperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('view', SystemOperator::class);

        return new OperatorCollection(
            SystemOperator::applyMode($request->trashed)
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
        Gate::authorize('create', SystemOperator::class);

        $request->validate([]);

        return SystemOperator::storeRecord($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\System\Models\SystemOperator $systemOperator
     * @return \Illuminate\Http\Response
     */
    public function show(SystemOperator $systemOperator)
    {
        Gate::authorize('show', $systemOperator);

        return new OperatorShowResource($systemOperator);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\System\Models\SystemOperator $systemOperator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemOperator $systemOperator)
    {
        Gate::authorize('update', $systemOperator);

        $request->validate([]);

        return SystemOperator::updateRecord($request, $systemOperator);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\System\Models\SystemOperator $systemOperator
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemOperator $systemOperator)
    {
        Gate::authorize('delete', $systemOperator);

        return SystemOperator::deleteRecord($systemOperator);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemOperator $systemOperator
     * @return \Illuminate\Http\Response
     */
    public function restore(SystemOperator $systemOperator)
    {
        Gate::authorize('restore', $systemOperator);

        return SystemOperator::restoreRecord($systemOperator);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemOperator $systemOperator
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(SystemOperator $systemOperator)
    {
        Gate::authorize('destroy', $systemOperator);

        return SystemOperator::destroyRecord($systemOperator);
    }
}
