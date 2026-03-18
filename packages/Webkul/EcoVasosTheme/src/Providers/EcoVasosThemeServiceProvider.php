<?php

namespace Webkul\EcoVasosTheme\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class EcoVasosThemeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // 🔥 Registrar namespace das views
        $this->loadViewsFrom(
            __DIR__ . '/../Resources/views',
            'eco-vasos-theme'
        );

        // 🔥 Registrar namespace dos components
        Blade::componentNamespace(
            'Webkul\\EcoVasosTheme\\View\\Components',
            'eco-vasos-theme'
        );

        // (opcional) publish
        $this->publishes([
            __DIR__.'/../Resources/views' => resource_path('themes/ecovasos-theme/views'),
        ], 'ecovasos-theme-views');
    }
}
