<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Include Past Snapshots
    |--------------------------------------------------------------------------
    |
    | If you periodically run the horizon:snapshot command, it will reset
    | the values exposed to Prometheus to 0, this leading to Prometheus
    | reading counters starting from 0 each time it is ran.
    | Because most of the counters are gauges, this means that once they are
    | reset, running rate() methods on given gauges will show dropped
    | values to 0, making a confusion to the actual rate of metrics.
    |
    | You can prevent that by enabling this setting. On the current values
    | for throughput, jobs, etc., the values from snapshots will also be
    | included. This may lead to graphs showing more jobs if you enable
    | this after you had it disabled.
    |
    | It is recommended to keep it enabled, or to disable it and
    | make sure the horizon:snapshot is never called.
    |
    */

    'include_snapshots' => (bool) env('HORIZON_EXPORTER_SNAPSHOTS', true),

];
