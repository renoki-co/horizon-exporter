<?php

namespace RenokiCo\HorizonExporter\Metrics;

use Laravel\Horizon\Contracts\JobRepository;
use RenokiCo\HorizonExporter\Metric;

class JobsByType extends Metric
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
        $statuses = [
            'recent_jobs' => 'countRecent',
            'failed_jobs' => 'countFailed',
            'pending_jobs' => 'countPending',
            'completed_jobs' => 'countCompleted',
            'recent_failed_jobs' => 'countRecentlyFailed',
        ];

        foreach ($statuses as $status => $method) {
            $this->collector->set(
                value: app(JobRepository::class)->{$method}(),
                labels: ['type' => str_replace('_jobs', '', $status)],
            );
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
            name: 'horizon_jobs_by_type',
            help: 'Get total processed jobs into all queues by specific type (i.e. completed, pending, etc.).',
            labels: ['type'],
        );
    }
}
