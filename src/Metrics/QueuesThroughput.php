<?php

namespace RenokiCo\HorizonExporter\Metrics;

use Laravel\Horizon\Contracts\MetricsRepository;
use RenokiCo\LaravelExporter\GaugeMetric;

class QueuesThroughput extends GaugeMetric
{
    /**
     * The group this metric gets shown into.
     *
     * @var string|null
     */
    public static $showsOnGroup = 'horizon-metrics';

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

                $this->set(
                    value: $value,
                    labels: ['queue' => $queue],
                );
            }
        }
    }

    /**
     * Get the metric name.
     *
     * @return string
     */
    protected function name(): string
    {
        return 'horizon_queue_throughput';
    }

    /**
     * Get the metric help.
     *
     * @return string
     */
    protected function help(): string
    {
        return 'Get total jobs throughput by queue.';
    }

    /**
     * Get the metric allowed labels.
     *
     * @return array
     */
    protected function allowedLabels(): array
    {
        return ['queue'];
    }
}
