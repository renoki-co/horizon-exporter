<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Horizon Exporter Domain
    |--------------------------------------------------------------------------
    |
    | This is the subdomain where Horizon Exporter will be accessible from.
    | If this setting is null, Horizon will reside under the same domain as
    | the application. Otherwise, this value will serve as the subdomain.
    |
    */

    'domain' => null,

    /*
    |--------------------------------------------------------------------------
    | Horizon Exporter Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where Horizon Exporter will be accessible from.
    | Feel free to change this path to anything you like.
    |
    */

    'path' => 'horizon-exporter',

    /*
    |--------------------------------------------------------------------------
    | Horizon Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will get attached onto each Horizon Exporter route,
    | giving you the chance to add your own middleware to this list or change
    | any of the existing middleware. Or, you can simply stick with this list.
    |
    */

    'middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Include Past Snapshots
    |--------------------------------------------------------------------------
    |
    | If you periodically run the horizon:snapshot command, it will reset
    | the values exposed to Prometheus to 0, this leading to Prometheus
    | reading counters starting from 0 each time it is ran.
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
