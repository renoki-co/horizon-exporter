<?php

namespace RenokiCo\HorizonExporter\Metrics;

use Laravel\Horizon\Contracts\SupervisorRepository;
use RenokiCo\HorizonExporter\Metric;

class MasterSupervisorsStatus extends Metric
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
        if ($supervisors = app(SupervisorRepository::class)->all()) {
            foreach ($supervisors as $supervisor) {
                $this->collector->set($supervisor->status === 'paused' ? 1 : 2, [
                    'name' => $supervisor->name,
                    'master' => $supervisor->master,
                    'pid' => $supervisor->pid,
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
            name: 'horizon_supervisor_status',
            help: 'The status of the Supervisor process. 0 = inactive, 1 = paused, 2 = running.',
            labels: ['name', 'master', 'pid'],
        );
    }
}
