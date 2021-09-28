<?php

namespace RenokiCo\HorizonExporter\Metrics;

use Laravel\Horizon\Contracts\MasterSupervisorRepository;
use RenokiCo\LaravelExporter\GaugeMetric;

class MasterStatus extends GaugeMetric
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
        if ($masters = app(MasterSupervisorRepository::class)->all()) {
            foreach ($masters as $master) {
                if (! $master) {
                    continue;
                }

                $this->set(
                    value: $master->status === 'paused' ? 1 : 2,
                    labels: ['name' => $master->name, 'pid' => $master->pid],
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
        return 'horizon_master_status';
    }

    /**
     * Get the metric help.
     *
     * @return string
     */
    protected function help(): string
    {
        return 'That status of the Master Horizon process. 0 = inactive, 1 = paused, 2 = running.';
    }

    /**
     * Get the metric allowed labels.
     *
     * @return array
     */
    protected function allowedLabels(): array
    {
        return ['name', 'pid'];
    }
}
