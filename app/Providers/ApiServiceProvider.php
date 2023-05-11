<?php

namespace App\Providers;

use App\Helpers\DatabasePopulator;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        DatabasePopulator::populateDatabase();
    }
}
