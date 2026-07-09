<?php

namespace Module\System\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\System\Models\SystemModule;
use Module\System\Http\Resources\ModuleCollection;
use Module\System\Http\Resources\ModuleShowResource;
use Monoland\Platform\Services\GitModule;

class SystemModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('view', SystemModule::class);

        return new ModuleCollection(
            SystemModule::applyMode($request->trashed)
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
        Gate::authorize('create', SystemModule::class);

        $request->validate([]);

        return SystemModule::storeRecord($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\System\Models\SystemModule $systemModule
     * @return \Illuminate\Http\Response
     */
    public function show(SystemModule $systemModule)
    {
        Gate::authorize('show', $systemModule);

        return new ModuleShowResource($systemModule);
    }

    /**
     * Undocumented function
     *
     * @param SystemModule $systemModule
     * @return void
     */
    public function checkForUpdate(SystemModule $systemModule)
    {
        GitModule::fetch($systemModule->slug);

        $isProduction = app()->environment('production');

        // jika env == local, maka updated_version = last commit
        // jika env == production, maka updated_version = last tag
        $currentVersion = $isProduction
            ? GitModule::localTagLast($systemModule->slug)
            : GitModule::localCommitLast($systemModule->slug);

        $updatedVersion = $isProduction
            ? GitModule::remoteTagLast($systemModule->slug)
            : GitModule::remoteCommitLast($systemModule->slug);

        return response()->json([
            // true = update exists | false = its last update
            'status' => $currentVersion !== $updatedVersion,

            'current_version' => $currentVersion,
            'updated_version' => $updatedVersion,

            // jika env == local, maka updated_notes = commit message
            // jika env == production, maka updated_notes = release note
            'updated_notes' => null,
        ], 200);
    }

    /**
     * processUpdate function
     *
     * @param SystemModule $systemModule
     * @return void
     */
    public function processUpdate(SystemModule $systemModule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\System\Models\SystemModule $systemModule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemModule $systemModule)
    {
        Gate::authorize('update', $systemModule);

        $request->validate([]);

        return SystemModule::updateRecord($request, $systemModule);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\System\Models\SystemModule $systemModule
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemModule $systemModule)
    {
        Gate::authorize('delete', $systemModule);

        return SystemModule::deleteRecord($systemModule);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemModule $systemModule
     * @return \Illuminate\Http\Response
     */
    public function restore(SystemModule $systemModule)
    {
        Gate::authorize('restore', $systemModule);

        return SystemModule::restoreRecord($systemModule);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemModule $systemModule
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(SystemModule $systemModule)
    {
        Gate::authorize('destroy', $systemModule);

        return SystemModule::destroyRecord($systemModule);
    }
}
