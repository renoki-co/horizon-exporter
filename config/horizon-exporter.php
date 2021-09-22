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

];
