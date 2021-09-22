<?php

namespace RenokiCo\HorizonExporter\Test;

use Illuminate\Support\ServiceProvider;
use Laravel\Horizon\Horizon;

class TestServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        Horizon::auth(function ($user) {
            return true;
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
