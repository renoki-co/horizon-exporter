<?php

namespace RenokiCo\HorizonExporter\Metrics;

use Laravel\Horizon\Contracts\MasterSupervisorRepository;
use RenokiCo\LaravelExporter\Metric;

class MasterStatus extends Metric
{
    /**
     * The collector to store the metric.
     *
     * @var \Prometheus\Gauge
     */
    protected $collector;

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
        if ($masters = app(MasterSupervisorRepository::class)->all()) {
            foreach ($masters as $master) {
                if (! $master) {
                    continue;
                }

                $this->collector->set($master->status === 'paused' ? 1 : 2, [
                    'name' => $master->name,
                    'pid' => $master->pid,
                ]);
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
            name: 'horizon_master_status',
            help: 'That status of the Master Horizon process. 0 = inactive, 1 = paused, 2 = running.',
            labels: ['name', 'pid'],
        );
    }
}
