<?php

namespace Module\System\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\System\Models\SystemModule;
use Module\System\Models\SystemAbility;
use Module\System\Http\Resources\AbilityCollection;
use Module\System\Http\Resources\AbilityShowResource;

class SystemAbilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Module\System\Models\SystemModule $systemModule
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, SystemModule $systemModule)
    {
        Gate::authorize('view', SystemAbility::class);

        return new AbilityCollection(
            $systemModule
                ->abilities()
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
     * @param  \Module\System\Models\SystemModule $systemModule
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, SystemModule $systemModule)
    {
        Gate::authorize('create', SystemAbility::class);

        $request->validate([]);

        return SystemAbility::storeRecord($request, $systemModule);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\System\Models\SystemModule $systemModule
     * @param  \Module\System\Models\SystemAbility $systemAbility
     * @return \Illuminate\Http\Response
     */
    public function show(SystemModule $systemModule, SystemAbility $systemAbility)
    {
        Gate::authorize('show', $systemAbility);

        return new AbilityShowResource($systemAbility);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\System\Models\SystemModule $systemModule
     * @param  \Module\System\Models\SystemAbility $systemAbility
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemModule $systemModule, SystemAbility $systemAbility)
    {
        Gate::authorize('update', $systemAbility);

        $request->validate([]);

        return SystemAbility::updateRecord($request, $systemAbility);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\System\Models\SystemModule $systemModule
     * @param  \Module\System\Models\SystemAbility $systemAbility
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemModule $systemModule, SystemAbility $systemAbility)
    {
        Gate::authorize('delete', $systemAbility);

        return SystemAbility::deleteRecord($systemAbility);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemAbility $systemAbility
     * @return \Illuminate\Http\Response
     */
    public function restore(SystemModule $systemModule, SystemAbility $systemAbility)
    {
        Gate::authorize('restore', $systemAbility);

        return SystemAbility::restoreRecord($systemAbility);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemAbility $systemAbility
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(SystemModule $systemModule, SystemAbility $systemAbility)
    {
        Gate::authorize('destroy', $systemAbility);

        return SystemAbility::destroyRecord($systemAbility);
    }
}