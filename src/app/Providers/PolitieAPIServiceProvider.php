<?php

namespace Politie\Providers;

use Illuminate\Support\ServiceProvider;

class PolitieAPIServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations/2020_08_28_214954_fill_tables_table.php');
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }
}
