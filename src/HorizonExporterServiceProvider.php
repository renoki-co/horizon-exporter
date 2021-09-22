<?php

namespace RenokiCo\HorizonExporter;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Prometheus\CollectorRegistry;
use Prometheus\Storage\InMemory;
use RenokiCo\HorizonExporter\Metrics\JobsByType;
use RenokiCo\HorizonExporter\Metrics\JobsRuntime;
use RenokiCo\HorizonExporter\Metrics\JobsThroughput;
use RenokiCo\HorizonExporter\Metrics\MasterStatus;
use RenokiCo\HorizonExporter\Metrics\MasterSupervisorsStatus;
use RenokiCo\HorizonExporter\Metrics\QueuesRuntime;
use RenokiCo\HorizonExporter\Metrics\QueuesThroughput;

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

        HorizonExporter::setRegistry(new CollectorRegistry(new InMemory));

        HorizonExporter::metrics([
            JobsByType::class,
            JobsRuntime::class,
            JobsThroughput::class,
            MasterStatus::class,
            MasterSupervisorsStatus::class,
            QueuesRuntime::class,
            QueuesThroughput::class,
        ]);

        Route::group([
            'domain' => config('horizon-exporter.domain', null),
            'prefix' => config('horizon-exporter.path'),
            'middleware' => config('horizon-exporter.middleware', 'web'),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
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
