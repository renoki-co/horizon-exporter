<?php

namespace RenokiCo\HorizonExporter\Test;

use Illuminate\Support\Facades\Queue;
use Laravel\Horizon\Contracts\HorizonCommandQueue;
use Laravel\Horizon\Contracts\MasterSupervisorRepository;
use Laravel\Horizon\Contracts\SupervisorRepository;
use Laravel\Horizon\MasterSupervisorCommands\AddSupervisor;
use Laravel\Horizon\Supervisor;
use Laravel\Horizon\SupervisorOptions;
use RenokiCo\HorizonExporter\HorizonExporter;

class MetricsTest extends TestCase
{
    public function test_master_status_metric()
    {
        $master1 = $this->newMasterSupervisor('master-1');

        $master2 = $this->newMasterSupervisor('master-2', function ($master) {
            $master->pause();
        });

        resolve(MasterSupervisorRepository::class)->update($master1);
        resolve(MasterSupervisorRepository::class)->update($master2);

        $response = HorizonExporter::exportAsPlainText();

        $this->assertStringContainsString("horizon_master_status{name=\"master-1\",pid=\"{$master1->pid()}\"} 2", $response);
        $this->assertStringContainsString("horizon_master_status{name=\"master-2\",pid=\"{$master2->pid()}\"} 1", $response);
    }

    public function test_master_supervisors_status_metric()
    {
        $master = $this->newMasterSupervisor('master-1');

        $supervisor = new Supervisor(new SupervisorOptions('master-1:name', 'redis'));
        resolve(SupervisorRepository::class)->update($supervisor);

        $master->loop();

        new AddSupervisor;

        resolve(HorizonCommandQueue::class)->push(
            $master->commandQueue(),
            AddSupervisor::class,
            (new SupervisorOptions('supervisor-1', 'redis')
        )->toArray());

        $master->loop();

        $response = HorizonExporter::exportAsPlainText();

        $this->assertStringContainsString(
            "horizon_supervisor_status{name=\"{$supervisor->name}\",master=\"{$master->name}\",pid=\"{$master->pid()}\"} 2",
            $response,
        );
    }

    public function test_queues_throughput_metric()
    {
        resolve(MasterSupervisorRepository::class)->update(
            $master = $this->newMasterSupervisor('master-1')
        );

        Queue::push(new Jobs\BasicJob);
        Queue::push(new Jobs\BasicJob);
        Queue::push(new Jobs\BasicJob);

        $this->work();
        $this->work();
        $this->work();

        $response = HorizonExporter::exportAsPlainText();

        $this->assertStringContainsString(
            "horizon_queue_throughput{queue=\"default\"} 3",
            $response,
        );
    }

    public function test_jobs_throughput_metric()
    {
        resolve(MasterSupervisorRepository::class)->update(
            $master = $this->newMasterSupervisor('master-1')
        );

        Queue::push(new Jobs\BasicJob);
        Queue::push(new Jobs\BasicJob);
        Queue::push(new Jobs\BasicJob);
        Queue::push(new Jobs\BasicJob2);

        $this->work();
        $this->work();
        $this->work();
        $this->work();

        $response = HorizonExporter::exportAsPlainText();

        $this->assertStringContainsString(
            "horizon_job_throughput{job=\"RenokiCo\\\\HorizonExporter\\\\Test\\\\Jobs\\\\BasicJob\"} 3",
            $response,
        );

        $this->assertStringContainsString(
            "horizon_job_throughput{job=\"RenokiCo\\\\HorizonExporter\\\\Test\\\\Jobs\\\\BasicJob2\"} 1",
            $response,
        );
    }

    public function test_queues_runtime_metric()
    {
        resolve(MasterSupervisorRepository::class)->update(
            $master = $this->newMasterSupervisor('master-1')
        );

        Queue::push(new Jobs\BasicJob);
        Queue::push(new Jobs\BasicJob);
        Queue::push(new Jobs\BasicJob);

        $this->work();
        $this->work();
        $this->work();

        $response = HorizonExporter::exportAsPlainText();

        $this->assertStringContainsString(
            "horizon_queue_runtime{queue=\"default\"}",
            $response,
        );
    }

    public function test_jobs_runtime_metric()
    {
        resolve(MasterSupervisorRepository::class)->update(
            $master = $this->newMasterSupervisor('master-1')
        );

        Queue::push(new Jobs\BasicJob);
        Queue::push(new Jobs\BasicJob);
        Queue::push(new Jobs\BasicJob);
        Queue::push(new Jobs\BasicJob2);

        $this->work();
        $this->work();
        $this->work();
        $this->work();

        $response = HorizonExporter::exportAsPlainText();

        $this->assertStringContainsString(
            "horizon_job_runtime{job=\"RenokiCo\\\\HorizonExporter\\\\Test\\\\Jobs\\\\BasicJob\"}",
            $response,
        );

        $this->assertStringContainsString(
            "horizon_job_runtime{job=\"RenokiCo\\\\HorizonExporter\\\\Test\\\\Jobs\\\\BasicJob2\"}",
            $response,
        );
    }

    public function test_jobs_throughput_by_type()
    {
        resolve(MasterSupervisorRepository::class)->update(
            $master = $this->newMasterSupervisor('master-1')
        );

        Queue::push(new Jobs\BasicJob);
        Queue::push(new Jobs\BasicJob);
        Queue::push(new Jobs\BasicJob);
        Queue::push(new Jobs\BasicJob2);
        Queue::push(new Jobs\FailedJob);
        Queue::push(new Jobs\BasicJob);

        $this->work();
        $this->work();
        $this->work();
        $this->work();
        $this->work();

        $response = HorizonExporter::exportAsPlainText();

        $this->assertStringContainsString(
            "horizon_jobs_by_type{type=\"completed\"} 4",
            $response,
        );

        $this->assertStringContainsString(
            "horizon_jobs_by_type{type=\"failed\"} 1",
            $response,
        );

        $this->assertStringContainsString(
            "horizon_jobs_by_type{type=\"pending\"} 1",
            $response,
        );

        $this->assertStringContainsString(
            "horizon_jobs_by_type{type=\"recent\"} 6",
            $response,
        );

        $this->assertStringContainsString(
            "horizon_jobs_by_type{type=\"recent_failed\"} 1",
            $response,
        );
    }

    public function test_memory_is_kept_between_sessions()
    {
        resolve(MasterSupervisorRepository::class)->update(
            $master = $this->newMasterSupervisor('master-1')
        );

        Queue::push(new Jobs\BasicJob);
        Queue::push(new Jobs\BasicJob);
        Queue::push(new Jobs\BasicJob);

        $this->work();
        $this->work();
        $this->work();

        $response = HorizonExporter::exportAsPlainText();

        $this->assertStringContainsString(
            "horizon_queue_throughput{queue=\"default\"} 3",
            $response,
        );

        Queue::push(new Jobs\BasicJob);
        Queue::push(new Jobs\BasicJob);
        Queue::push(new Jobs\BasicJob);

        $this->work();
        $this->work();
        $this->work();

        $response = HorizonExporter::exportAsPlainText();

        $this->assertStringContainsString(
            "horizon_queue_throughput{queue=\"default\"} 6",
            $response,
        );
    }
}
