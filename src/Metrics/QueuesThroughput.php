<?php

namespace RenokiCo\HorizonExporter\Metrics;

use Laravel\Horizon\Contracts\MetricsRepository;
use RenokiCo\LaravelExporter\Metric;

class QueuesThroughput extends Metric
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
                $value = app(MetricsRepository::class)->throughputForQueue($queue);

                if (config('horizon-exporter.include_snapshots')) {
                    foreach (app(MetricsRepository::class)->snapshotsForQueue($queue) as $snapshot) {
                        $value += $snapshot->throughput;
                    }
                }

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
            name: 'horizon_queue_throughput',
            help: 'Get total jobs throughput by queue.',
            labels: ['queue'],
        );
    }
}
