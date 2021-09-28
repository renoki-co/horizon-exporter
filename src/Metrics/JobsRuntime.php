<?php

namespace RenokiCo\HorizonExporter\Metrics;

use Laravel\Horizon\Contracts\MetricsRepository;
use RenokiCo\LaravelExporter\GaugeMetric;
use RenokiCo\LaravelExporter\Metric;

class JobsRuntime extends GaugeMetric
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
                $value = collect(config('horizon-exporter.include_snapshots') ? app(MetricsRepository::class)->snapshotsForJob($job) : [])
                    ->merge([(object) ['runtime' => app(MetricsRepository::class)->runtimeForJob($job)]])
                    ->average('runtime');

                $this->set(
                    value: $value,
                    labels: ['job' => $job],
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
        return 'horizon_job_runtime';
    }

    /**
     * Get the metric help.
     *
     * @return string
     */
    protected function help(): string
    {
        return 'Get total workload runtime by job name.';
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
