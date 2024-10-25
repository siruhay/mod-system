<?php

namespace Module\System\LaravelPulse;

use Laravel\Pulse\Livewire\SlowQueries;
use Laravel\Pulse\Recorders\SlowQueries as SlowQueriesRecorder;

class PulseSlowQueries extends SlowQueries
{
    /**
     * fetch function
     *
     * @return array
     */
    public function fetch(): array
    {
        [$slowQueries, $time, $runAt] = $this->remember(
            fn () => $this->aggregate(
                'slow_query',
                ['max', 'count'],
                match ('slowest') {
                    'count' => 'count',
                    default => 'max',
                },
            )->map(function ($row) {
                [$sql, $location] = json_decode($row->key, flags: JSON_THROW_ON_ERROR);

                return (object) [
                    'sql' => $sql,
                    'location' => $location,
                    'slowest' => $row->max,
                    'count' => $row->count,
                    'threshold' => $this->threshold($sql, SlowQueriesRecorder::class),
                ];
            }),
            'slowest',
        );

        return [
            'time' => $time,
            'runAt' => $runAt,
            'data' => $slowQueries,
        ];
    }
}