<?php

namespace Webkul\MelhorEnvio\Providers;

use Illuminate\Support\ServiceProvider;

class MelhorEnvioServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/carriers.php', 'carriers');
        $this->mergeConfigFrom(__DIR__.'/../Config/system.php', 'core');
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Http/routes.php');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'melhorenvio');
    }
}
