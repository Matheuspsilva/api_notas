<?php

namespace App\Console\Commands;

use App\Services\DatabasePopulator;
use Illuminate\Console\Command;

class PopulateDatabase extends Command
{
    protected $signature = 'db:populate';
    protected $description = 'Populate the database with data.';

    public function __construct(DatabasePopulator $populator)
    {
        parent::__construct();
        $this->populator = $populator;
    }

    public function handle()
    {
        $this->populator->populate();
        $this->info('Database populated.');
    }
}
