<?php

namespace RenokiCo\HorizonExporter\Test;

use Illuminate\Support\Facades\Queue;
use Laravel\Horizon\Contracts\MasterSupervisorRepository;

class HttpTest extends TestCase
{
    public function test_http_metrics()
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

        $this->get('/exporter/horizon-metrics')
            ->assertSee('horizon_jobs_by_type{type="completed"} 3', escape: false);
    }
}
