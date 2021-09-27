<?php

namespace RenokiCo\HorizonExporter\Test;

use Closure;
use Illuminate\Queue\WorkerOptions;
use Illuminate\Support\Facades\Redis;
use Laravel\Horizon\Horizon;
use Laravel\Horizon\MasterSupervisor;
use Laravel\Horizon\SupervisorCommandString;
use Laravel\Horizon\WorkerCommandString;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        Redis::flushall();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        Redis::flushall();
        WorkerCommandString::reset();
        SupervisorCommandString::reset();
        Horizon::$authUsing = null;
    }

    /**
     * {@inheritdoc}
     */
    protected function getPackageProviders($app)
    {
        return [
            \Laravel\Horizon\HorizonServiceProvider::class,
            \RenokiCo\LaravelExporter\LaravelExporterServiceProvider::class,
            \RenokiCo\HorizonExporter\HorizonExporterServiceProvider::class,
            TestServiceProvider::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('queue.default', 'redis');
        $app['config']->set('app.key', 'wslxrEFGWY6GfGhvN9L3wH3KSRJQQpBD');
    }

    /**
     * Run the next job on the queue.
     *
     * @param  int  $times
     * @return void
     */
    protected function work($times = 1)
    {
        for ($i = 0; $i < $times; $i++) {
            $this->worker()->runNextJob(
                'redis', 'default', $this->workerOptions()
            );
        }
    }

    /**
     * Get the queue worker instance.
     *
     * @return \Illuminate\Queue\Worker
     */
    protected function worker()
    {
        return app('queue.worker');
    }

    /**
     * Get the options for the worker.
     *
     * @return \Illuminate\Queue\WorkerOptions
     */
    protected function workerOptions()
    {
        return tap(new WorkerOptions, function ($options) {
            $options->sleep = 0;
            $options->maxTries = 1;
        });
    }

    /**
     * Create a new master supervisor.
     *
     * @param  string  $name
     * @param  \Closure|null  $callback
     * @return MasterSupervisor
     */
    protected function newMasterSupervisor(string $name, Closure $callback = null)
    {
        $master = new MasterSupervisor;
        $master->name = $name;

        if ($callback) {
            $callback($master);
        }

        return $master;
    }
}
