<?php

namespace Module\System\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;

trait Impersonate
{
    /**
     * The putImpersonate function
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function putImpersonate(): JsonResponse
    {
        Session::put('impersonate', $this->id);

        return response()->json([
            'success' => true,
        ], 200);
    }

    /**
     * The forgetImpersonate function
     *
     * @return JsonResponse
     */
    public function forgetImpersonate(): JsonResponse
    {
        Session::forget('impersonate');

        return response()->json([
            'success' => true,
        ], 200);
    }

    /**
     * impersonateTake function
     *
     * @param [type] $source
     * @return boolean
     */
    public function impersonateTake($source): bool
    {
        try {
            Session::put('impersonate_source_id', $source->id);
            Session::put('impersonate_origin_id', $this->id);
            Session::put('impersonate_origin_name', $this->name);

            return true;
        } catch (\Exception $e) {
            unset($e);

            return false;
        }
    }

    /**
     * impersonateLeave function
     *
     * @return boolean
     */
    public function impersonateLeave(): bool
    {
        try {
            Session::forget('impersonate_source_id');
            Session::forget('impersonate_origin_id');
            Session::forget('impersonate_origin_name');

            return true;
        } catch (\Exception $e) {
            unset($e);

            return false;
        }
    }

    /**
     * The hasImpersonate function
     *
     * @return boolean
     */
    public function hasImpersonate(): bool
    {
        return Session::has('impersonate_source_id');
    }
}
