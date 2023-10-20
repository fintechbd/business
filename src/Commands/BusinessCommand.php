<?php

namespace Fintech\Business\Commands;

use Illuminate\Console\Command;

class BusinessCommand extends Command
{
    public $signature = 'business';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
