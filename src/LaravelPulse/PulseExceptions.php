<?php

namespace Module\System\LaravelPulse;

use Carbon\CarbonImmutable;
use Laravel\Pulse\Livewire\Exceptions;

class PulseExceptions extends Exceptions
{
    public function fetch(): array
    {
        [$exceptions, $time, $runAt] = $this->remember(
            fn () => $this->aggregate(
                'exception',
                ['max', 'count'],
                match ('slowest') {
                    'latest' => 'max',
                    default => 'count'
                },
            )->map(function ($row) {
                [$class, $location] = json_decode($row->key, flags: JSON_THROW_ON_ERROR);

                return (object) [
                    'class' => $class,
                    'location' => $location,
                    'latest' => CarbonImmutable::createFromTimestamp($row->max)->diffForHumans(),
                    'count' => $row->count,
                ];
            }),
            'slowest'
        );

        return [
            'time' => $time,
            'runAt' => $runAt,
            'data' => $exceptions,
        ];
    }
}