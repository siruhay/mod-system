<?php

namespace Module\System\Http\Controllers;

use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Module\System\Requests\LogoutOtherDeviceRequest;

class DashboardController extends Controller
{
    /**
     * accountIndex function
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function accountIndex(Request $request): JsonResponse
    {
        return response()->json([
            'record' => [
                'chartx' => [
                    'data' => []
                ]
            ],
        ], 200);
    }

    /**
     * systemIndex function
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function systemIndex(Request $request): JsonResponse
    {
        return response()->json([
            'record' => [],
            // 'pulse' =>  [
            //     'exceptions' => (new PulseExceptions())->fetch(),
            //     'slow_query' => (new PulseSlowQueries())->fetch(),
            //     'usages' => (new PulseUsage())->fetch(),
            // ],
        ], 200);
    }

    /**
     * createAgent function
     *
     * @param [type] $user_agent
     * @return Agent
     */
    protected function createAgent($user_agent): Agent
    {
        return tap(new Agent(), function ($agent) use ($user_agent) {
            $agent->setUserAgent($user_agent);
        });
    }

    /**
     * mapUserSessions function
     *
     * @param Request $request
     * @return void
     */
    protected function mapUserSessions(Request $request)
    {
        return collect(
            DB::table(env('SESSION_TABLE'))
                ->where('user_id', $request->user()->getAuthIdentifier())
                ->orderBy('last_activity', 'desc')
                ->get()
        )->reduce(function ($results, $session) use ($request) {
            $session    = (object) $session;
            $agent      = $this->createAgent($session->user_agent);

            array_push($results, (object) [
                'agent' => [
                    'is_mobile' => $agent->isMobile(),
                    'is_tablet' => $agent->isTablet(),
                    'platform'  => $agent->platform(),
                    'browser'   => $agent->browser(),
                ],
                'geolocation'   => json_decode($session->geolocation),
                'ip_address'    => $session->ip_address,
                'is_current_device' => $session->id === $request->session()->getId(),
                'last_active'   => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ]);

            return $results;
        }, []);
    }

    /**
     * logoutOtherDevices function
     *
     * @param LogoutOtherDeviceRequest $request
     * @return void
     */
    public function logoutOtherDevices(LogoutOtherDeviceRequest $request)
    {
        DB::table(env('SESSION_TABLE'))
            ->where('user_id', $request->user()->getAuthIdentifier())
            ->whereNot('id', $request->session()->getId())
            ->delete();

        return response()->json([
            'sessions' => $this->mapUserSessions($request)
        ], 200);
    }

    /**
     * setting function
     *
     * @param Request $request
     * @return void
     */
    public function setting(Request $request)
    {
        return response()->json([
            'record' => [
                /** PROFILE */
                'username' => $request->user()->name,
                'theme' => $request->user()->theme,
                'highlight' => $request->user()->highlight,

                /** PASSWORD */
                'current_password' => null,
                'password' => null,
                'password_confirmation' => null,

                /** TWO-FACTOR */
                'recovery_code_two_factor' => !is_null($request->user()->two_factor_secret) && !is_null($request->user()->two_factor_confirmed_at) ? $request->user()->recoveryCodes() : [],
                'svg_two_factor' => !is_null($request->user()->two_factor_secret) && is_null($request->user()->two_factor_confirmed_at) ? $request->user()->twoFactorQrCodeSvg() : null,
                'url_two_factor' => !is_null($request->user()->two_factor_secret) && is_null($request->user()->two_factor_confirmed_at) ? $request->user()->twoFactorQrCodeUrl() : null,
                'otp_two_factor' => null,
                'enabled_two_factor' => !is_null($request->user()->two_factor_secret),
                'confirmed_two_factor' => !is_null($request->user()->two_factor_confirmed_at),

                /** SESSION */
                'sessions' => $this->mapUserSessions($request)
            ],

            'setups' => [
                'combos' => [
                    'colors' => [
                        'red',
                        'pink',
                        'purple',
                        'deep-purple',
                        'indigo',
                        'blue',
                        'light-blue',
                        'cyan',
                        'teal',
                        'green',
                        'light-green',
                        'lime',
                        'yellow',
                        'amber',
                        'orange',
                        'deep-orange',
                        'brown',
                        'blue-grey',
                        'grey',
                        'white'
                    ]
                ]
            ]
        ]);
    }
}
