<?php

namespace RenokiCo\HorizonExporter\Metrics;

use Laravel\Horizon\Contracts\MetricsRepository;
use RenokiCo\HorizonExporter\Metric;

class QueuesThroughput extends Metric
{
    /**
     * The collector to store the metric.
     *
     * @var \Prometheus\Histogram
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
                $this->collector->observe(
                    value: app(MetricsRepository::class)->throughputForQueue($queue),
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
        return $this->collector = $this->registry->registerHistogram(
            namespace: $this->getNamespace(),
            name: 'horizon_queue_throughput',
            help: 'Get total jobs throughput by queue.',
            labels: ['queue'],
        );
    }
}
