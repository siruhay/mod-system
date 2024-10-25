<?php

namespace Module\System\LaravelPulse;

use Laravel\Pulse\Livewire\Usage;
use Module\System\Models\SystemUser;

class PulseUsage extends Usage
{
    public function fetch()
    {
        $type = $this->type ?? $this->usage;

        [$userRequestCounts, $time, $runAt] = $this->remember(
            function () use ($type) {
                $counts = $this->aggregate(
                    match ($type) {
                        'requests' => 'user_request',
                        'slow_requests' => 'slow_user_request',
                        'jobs' => 'user_job',
                    },
                    'count',
                    limit: 10,
                );

                $users = SystemUser::select('id', 'name', 'email')->whereIn('id', $counts->pluck('key'))->get();

                return $counts->map(fn ($row) => (object) [
                    'key' => $row->key,
                    'user' => $users->find($row->key),
                    'count' => (int) $row->count,
                ]);
            },
            $type
        );

        return [
            'time' => $time,
            'runAt' => $runAt,
            'data' => $userRequestCounts,
        ];
    }
}