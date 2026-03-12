<?php

namespace Webkul\EcoVasosTheme\Providers;

use Illuminate\Support\ServiceProvider;

class EcoVasosThemeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../Resources/views' => resource_path('themes/ecovasos-theme/views'),
        ], 'ecovasos-theme-views');
    }
}
