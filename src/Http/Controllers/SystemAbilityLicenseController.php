<?php

namespace Module\System\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\System\Models\SystemAbility;
use Module\System\Models\SystemLicense;
use Module\System\Http\Resources\LicenseCollection;
use Module\System\Http\Resources\LicenseShowResource;

class SystemAbilityLicenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Module\System\Models\SystemAbility $systemAbility
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, SystemAbility $systemAbility)
    {
        Gate::authorize('view', SystemLicense::class);

        return new LicenseCollection(
            $systemAbility
                ->licenses()
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
        Gate::authorize('create', SystemLicense::class);

        $request->validate([]);

        return SystemLicense::storeRecord($request, $systemAbility);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\System\Models\SystemAbility $systemAbility
     * @param  \Module\System\Models\SystemLicense $systemLicense
     * @return \Illuminate\Http\Response
     */
    public function show(SystemAbility $systemAbility, SystemLicense $systemLicense)
    {
        Gate::authorize('show', $systemLicense);

        return new LicenseShowResource($systemLicense);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\System\Models\SystemAbility $systemAbility
     * @param  \Module\System\Models\SystemLicense $systemLicense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemAbility $systemAbility, SystemLicense $systemLicense)
    {
        Gate::authorize('update', $systemLicense);

        $request->validate([]);

        return SystemLicense::updateRecord($request, $systemLicense);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\System\Models\SystemAbility $systemAbility
     * @param  \Module\System\Models\SystemLicense $systemLicense
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemAbility $systemAbility, SystemLicense $systemLicense)
    {
        Gate::authorize('delete', $systemLicense);

        return SystemLicense::deleteRecord($systemLicense);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemLicense $systemLicense
     * @return \Illuminate\Http\Response
     */
    public function restore(SystemAbility $systemAbility, SystemLicense $systemLicense)
    {
        Gate::authorize('restore', $systemLicense);

        return SystemLicense::restoreRecord($systemLicense);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemLicense $systemLicense
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(SystemAbility $systemAbility, SystemLicense $systemLicense)
    {
        Gate::authorize('destroy', $systemLicense);

        return SystemLicense::destroyRecord($systemLicense);
    }
}