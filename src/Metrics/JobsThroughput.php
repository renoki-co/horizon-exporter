<?php

namespace RenokiCo\HorizonExporter\Metrics;

use Laravel\Horizon\Contracts\MetricsRepository;
use RenokiCo\HorizonExporter\Metric;

class JobsThroughput extends Metric
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
        if ($jobs = app(MetricsRepository::class)->measuredJobs()) {
            foreach ($jobs as $job) {
                $this->collector->observe(
                    value: app(MetricsRepository::class)->throughputForJob($job),
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
        return $this->collector = $this->registry->registerHistogram(
            namespace: $this->getNamespace(),
            name: 'horizon_job_throughput',
            help: 'Get total workload throughput by job name.',
            labels: ['job'],
        );
    }
}
