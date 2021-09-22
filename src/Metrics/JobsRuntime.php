<?php

namespace RenokiCo\HorizonExporter\Metrics;

use Laravel\Horizon\Contracts\MetricsRepository;
use RenokiCo\HorizonExporter\Metric;

class JobsRuntime extends Metric
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
        if ($jobs = app(MetricsRepository::class)->measuredJobs()) {
            foreach ($jobs as $job) {
                $this->collector->set(
                    value: app(MetricsRepository::class)->runtimeForJob($job),
                    labels: [
                        'job' => $job,
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
            name: 'horizon_job_runtime',
            help: 'Get total workload runtime by job name.',
            labels: ['job'],
        );
    }
}
