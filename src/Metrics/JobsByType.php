<?php

namespace RenokiCo\HorizonExporter\Metrics;

use Laravel\Horizon\Contracts\JobRepository;
use RenokiCo\LaravelExporter\GaugeMetric;

class JobsByType extends GaugeMetric
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
        $statuses = [
            'recent_jobs' => 'countRecent',
            'failed_jobs' => 'countFailed',
            'pending_jobs' => 'countPending',
            'completed_jobs' => 'countCompleted',
            'recent_failed_jobs' => 'countRecentlyFailed',
        ];

        foreach ($statuses as $status => $method) {
            $this->set(
                value: app(JobRepository::class)->{$method}(),
                labels: ['type' => str_replace('_jobs', '', $status)],
            );
        }
    }


    /**
     * Get the metric name.
     *
     * @return string
     */
    protected function name(): string
    {
        return 'horizon_jobs_by_type';
    }

    /**
     * Get the metric help.
     *
     * @return string
     */
    protected function help(): string
    {
        return 'Get total processed jobs into all queues by specific type (i.e. completed, pending, etc.).';
    }

    /**
     * Get the metric allowed labels.
     *
     * @return array
     */
    protected function allowedLabels(): array
    {
        return ['type'];
    }
}
