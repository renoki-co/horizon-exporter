<?php

namespace RenokiCo\HorizonExporter;

use Prometheus\CollectorRegistry;
use Illuminate\Support\Str;

abstract class Metric
{
    /**
     * The collector to store the metric.
     *
     * @var \Prometheus\Collector
     */
    protected $collector;

    /**
     * Initialize the metric.
     *
     * @param  \Prometheus\CollectorRegistry  $registry
     * @return void
     */
    public function __construct(
        protected CollectorRegistry &$registry,
    ) {
        //
    }

    /**
     * Get the namespace to publish the metric to.
     *
     * @return string
     */
    protected function getNamespace(): string
    {
        return Str::snake(config('app.name'));
    }

    /**
     * Perform the update call on the collector.
     *
     * @return void
     */
    abstract public function update(): void;

    /**
     * Register the collector to the registry.
     *
     * @return \Prometheus\Collector
     */
    abstract public function registerCollector();
}
