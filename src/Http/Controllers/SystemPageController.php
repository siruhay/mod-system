<?php

namespace Module\System\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\System\Models\SystemPage;
use Module\System\Models\SystemModule;
use Module\System\Http\Resources\PageCollection;
use Module\System\Http\Resources\PageShowResource;

class SystemPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Module\System\Models\SystemModule $systemModule
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, SystemModule $systemModule)
    {
        Gate::authorize('view', SystemPage::class);

        return new PageCollection(
            $systemModule
                ->pages()
                ->with(['parent'])
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
        Gate::authorize('create', SystemPage::class);

        $request->validate([]);

        return SystemPage::storeRecord($request, $systemModule);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\System\Models\SystemModule $systemModule
     * @param  \Module\System\Models\SystemPage $systemPage
     * @return \Illuminate\Http\Response
     */
    public function show(SystemModule $systemModule, SystemPage $systemPage)
    {
        Gate::authorize('show', $systemPage);

        return new PageShowResource($systemPage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\System\Models\SystemModule $systemModule
     * @param  \Module\System\Models\SystemPage $systemPage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemModule $systemModule, SystemPage $systemPage)
    {
        Gate::authorize('update', $systemPage);

        $request->validate([]);

        return SystemPage::updateRecord($request, $systemPage);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\System\Models\SystemModule $systemModule
     * @param  \Module\System\Models\SystemPage $systemPage
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemModule $systemModule, SystemPage $systemPage)
    {
        Gate::authorize('delete', $systemPage);

        return SystemPage::deleteRecord($systemPage);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemPage $systemPage
     * @return \Illuminate\Http\Response
     */
    public function restore(SystemModule $systemModule, SystemPage $systemPage)
    {
        Gate::authorize('restore', $systemPage);

        return SystemPage::restoreRecord($systemPage);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemPage $systemPage
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(SystemModule $systemModule, SystemPage $systemPage)
    {
        Gate::authorize('destroy', $systemPage);

        return SystemPage::destroyRecord($systemPage);
    }
}
