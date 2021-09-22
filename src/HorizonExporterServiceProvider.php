<?php

namespace RenokiCo\HorizonExporter;

use Illuminate\Support\ServiceProvider;

class HorizonExporterServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/horizon-exporter.php' => config_path('horizon-exporter.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__.'/../config/horizon-exporter.php', 'horizon-exporter'
        );
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
