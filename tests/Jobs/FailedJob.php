<?php

namespace RenokiCo\HorizonExporter\Test\Jobs;

use Illuminate\Bus\Queueable;

class FailedJob
{
    use Queueable;

    public function handle()
    {
        $this->onQueue('default2');

        throw new \Exception('Job Failed');
    }

    public function tags()
    {
        return ['first', 'second'];
    }
}
