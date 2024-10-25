<?php

namespace Module\System\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\System\Models\SystemAbility;
use Module\System\Models\SystemAbilityPage;
use Module\System\Http\Resources\AbilityPageCollection;
use Module\System\Http\Resources\AbilityPageShowResource;

class SystemAbilityPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Module\System\Models\SystemAbility $systemAbility
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, SystemAbility $systemAbility)
    {
        Gate::authorize('view', SystemAbilityPage::class);

        return new AbilityPageCollection(
            $systemAbility
                ->pages()
                ->with(['module', 'page', 'page.parent'])
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
     * @param  \Module\System\Models\SystemAbility $systemAbility
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, SystemAbility $systemAbility)
    {
        Gate::authorize('create', SystemAbilityPage::class);

        $request->validate([]);

        return SystemAbilityPage::storeRecord($request, $systemAbility);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\System\Models\SystemAbility $systemAbility
     * @param  \Module\System\Models\SystemAbilityPage $systemAbilityPage
     * @return \Illuminate\Http\Response
     */
    public function show(SystemAbility $systemAbility, SystemAbilityPage $systemAbilityPage)
    {
        Gate::authorize('show', $systemAbilityPage);

        return new AbilityPageShowResource($systemAbilityPage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\System\Models\SystemAbility $systemAbility
     * @param  \Module\System\Models\SystemAbilityPage $systemAbilityPage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemAbility $systemAbility, SystemAbilityPage $systemAbilityPage)
    {
        Gate::authorize('update', $systemAbilityPage);

        $request->validate([]);

        return SystemAbilityPage::updateRecord($request, $systemAbilityPage);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\System\Models\SystemAbility $systemAbility
     * @param  \Module\System\Models\SystemAbilityPage $systemAbilityPage
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemAbility $systemAbility, SystemAbilityPage $systemAbilityPage)
    {
        Gate::authorize('delete', $systemAbilityPage);

        return SystemAbilityPage::deleteRecord($systemAbilityPage);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemAbilityPage $systemAbilityPage
     * @return \Illuminate\Http\Response
     */
    public function restore(SystemAbility $systemAbility, SystemAbilityPage $systemAbilityPage)
    {
        Gate::authorize('restore', $systemAbilityPage);

        return SystemAbilityPage::restoreRecord($systemAbilityPage);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemAbilityPage $systemAbilityPage
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(SystemAbility $systemAbility, SystemAbilityPage $systemAbilityPage)
    {
        Gate::authorize('destroy', $systemAbilityPage);

        return SystemAbilityPage::destroyRecord($systemAbilityPage);
    }
}