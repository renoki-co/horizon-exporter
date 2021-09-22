<?php

namespace RenokiCo\HorizonExporter\Test\Jobs;

use Illuminate\Bus\Queueable;

class BasicJob2
{
    use Queueable;

    public function handle()
    {
        $this->onQueue('default2');
    }

    public function tags()
    {
        return ['first', 'second'];
    }
}
