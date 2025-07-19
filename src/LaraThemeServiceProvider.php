<?php

namespace DanielBaez\LaraTheme;

use DanielBaez\LaraTheme\Console\Commands\MakeThemeCommand;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
class LaraThemeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laratheme.php', 'laratheme');

        $this->commands([
            MakeThemeCommand::class,
        ]);

        $this->app->singleton('laratheme', function ($app) {
            return new LaraThemeManager($app['view'], $app['url']);
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/laratheme.php' => config_path('laratheme.php'),
        ], 'laratheme-config');

        $this->publishes([
            __DIR__.'/Stubs' => resource_path('stubs/laratheme'),
        ], 'laratheme-stubs');

        $default = config('laratheme.default');
        $active = config('laratheme.active');
        $viewPaths = base_path(config('laratheme.paths.views'));

        View::addNamespace('laratheme', [
            $viewPaths."/{$active}",
            $viewPaths."/{$default}",
            resource_path('views')
        ]);

        Blade::anonymousComponentNamespace($viewPaths."/{$default}/components", 'laratheme');
    }
}