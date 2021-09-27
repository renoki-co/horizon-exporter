<?php

namespace RenokiCo\HorizonExporter\Metrics;

use Laravel\Horizon\Contracts\MetricsRepository;
use RenokiCo\LaravelExporter\Metric;

class QueuesRuntime extends Metric
{
    /**
     * The collector to store the metric.
     *
     * @var \Prometheus\Gauge
     */
    protected $collector;

    /**
     * Perform the update call on the collector.
     *
     * @return void
     */
    public function update(): void
    {
        if ($queues = app(MetricsRepository::class)->measuredQueues()) {
            foreach ($queues as $queue) {
                $value = collect(config('horizon-exporter.include_snapshots') ? app(MetricsRepository::class)->snapshotsForQueue($queue) : [])
                    ->merge([(object) ['runtime' => app(MetricsRepository::class)->runtimeForQueue($queue)]])
                    ->average('runtime');

                $this->collector->set(
                    value: $value,
                    labels: [
                        'queue' => $queue,
                    ],
                );
            }
        }
    }

    /**
     * Register the collector to the registry.
     *
     * @return \Prometheus\Collector
     */
    public function registerCollector()
    {
        return $this->collector = $this->registry->registerGauge(
            namespace: $this->getNamespace(),
            name: 'horizon_queue_runtime',
            help: 'Get total jobs runtime by queue.',
            labels: ['queue'],
        );
    }
}
