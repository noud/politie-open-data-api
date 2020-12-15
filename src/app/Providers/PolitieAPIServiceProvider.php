<?php

namespace Politie\Providers;

use Illuminate\Support\ServiceProvider;

class PolitieAPIServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }
}
