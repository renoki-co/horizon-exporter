<?php

namespace RenokiCo\HorizonExporter\Metrics;

use Laravel\Horizon\Contracts\MetricsRepository;
use RenokiCo\LaravelExporter\GaugeMetric;

class JobsThroughput extends GaugeMetric
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
        if ($jobs = app(MetricsRepository::class)->measuredJobs()) {
            foreach ($jobs as $job) {
                $value = app(MetricsRepository::class)->throughputForJob($job);

                if (config('horizon-exporter.include_snapshots')) {
                    foreach (app(MetricsRepository::class)->snapshotsForJob($job) as $snapshot) {
                        $value += $snapshot->throughput;
                    }
                }

                $this->set(
                    value: $value,
                    labels: [
                        'job' => $job,
                    ],
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
        return 'horizon_job_throughput';
    }

    /**
     * Get the metric help.
     *
     * @return string
     */
    protected function help(): string
    {
        return 'Get total workload throughput by job name.';
    }

    /**
     * Get the metric allowed labels.
     *
     * @return array
     */
    protected function allowedLabels(): array
    {
        return ['job'];
    }
}
