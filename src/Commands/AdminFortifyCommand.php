<?php

namespace NagSamayam\AdminFortify\Commands;

use Illuminate\Console\Command;

class AdminFortifyCommand extends Command
{
    public $signature = 'skeleton';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
