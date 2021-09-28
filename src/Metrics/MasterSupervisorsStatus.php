<?php

namespace RenokiCo\HorizonExporter\Metrics;

use Laravel\Horizon\Contracts\SupervisorRepository;
use RenokiCo\LaravelExporter\GaugeMetric;

class MasterSupervisorsStatus extends GaugeMetric
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
        if ($supervisors = app(SupervisorRepository::class)->all()) {
            foreach ($supervisors as $supervisor) {
                $this->set(
                    value: $supervisor->status === 'paused' ? 1 : 2,
                    labels: [
                        'name' => $supervisor->name,
                        'master' => $supervisor->master,
                        'pid' => $supervisor->pid,
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
        return 'horizon_supervisor_status';
    }

    /**
     * Get the metric help.
     *
     * @return string
     */
    protected function help(): string
    {
        return 'That status of the Supervisor process. 0 = inactive, 1 = paused, 2 = running.';
    }

    /**
     * Get the metric allowed labels.
     *
     * @return array
     */
    protected function allowedLabels(): array
    {
        return ['name', 'master', 'pid'];
    }
}
