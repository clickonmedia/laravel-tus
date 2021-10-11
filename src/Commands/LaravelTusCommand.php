<?php

namespace Clickonmedia\LaravelTus\Commands;

use Illuminate\Console\Command;

class LaravelTusCommand extends Command
{
    public $signature = 'laravel-tus';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
