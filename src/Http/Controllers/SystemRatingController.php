<?php

namespace Module\System\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\System\Models\SystemRating;
use Module\System\Http\Resources\RatingCollection;
use Module\System\Http\Resources\RatingShowResource;

class SystemRatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('view', SystemRating::class);

        return new RatingCollection(
            SystemRating::applyMode($request->mode)
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
        Gate::authorize('create', SystemRating::class);

        $request->validate([]);

        return SystemRating::storeRecord($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\System\Models\SystemRating $systemRating
     * @return \Illuminate\Http\Response
     */
    public function show(SystemRating $systemRating)
    {
        Gate::authorize('show', $systemRating);

        return new RatingShowResource($systemRating);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\System\Models\SystemRating $systemRating
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemRating $systemRating)
    {
        Gate::authorize('update', $systemRating);

        $request->validate([]);

        return SystemRating::updateRecord($request, $systemRating);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\System\Models\SystemRating $systemRating
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemRating $systemRating)
    {
        Gate::authorize('delete', $systemRating);

        return SystemRating::deleteRecord($systemRating);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemRating $systemRating
     * @return \Illuminate\Http\Response
     */
    public function restore(SystemRating $systemRating)
    {
        Gate::authorize('restore', $systemRating);

        return SystemRating::restoreRecord($systemRating);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\System\Models\SystemRating $systemRating
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(SystemRating $systemRating)
    {
        Gate::authorize('destroy', $systemRating);

        return SystemRating::destroyRecord($systemRating);
    }
}
