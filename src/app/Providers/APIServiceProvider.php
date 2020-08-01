<?php

namespace Politie\Providers;

use Illuminate\Support\ServiceProvider;

class APIServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }
}
