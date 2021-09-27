<?php

namespace RenokiCo\HorizonExporter;

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
use RenokiCo\LaravelExporter\Exporter;

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

        Exporter::setRegistry(new CollectorRegistry(new InMemory));

        Exporter::metrics([
            JobsByType::class,
            JobsRuntime::class,
            JobsThroughput::class,
            MasterStatus::class,
            MasterSupervisorsStatus::class,
            QueuesRuntime::class,
            QueuesThroughput::class,
        ]);
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
