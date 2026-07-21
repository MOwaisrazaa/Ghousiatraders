<?php

namespace App\Providers;

use App\Services\AssetManager\AssetManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AssetManagerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('asset.manager', function () {
            return new AssetManager();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Register Blade directives
        $this->registerBladeDirectives();

        // Share the asset manager with all views
        view()->composer('*', function ($view) {
            $view->with('assetManager', app('asset.manager'));
        });
    }

    /**
     * Register custom Blade directives for asset management.
     *
     * @return void
     */
    protected function registerBladeDirectives()
    {
        // @styles - Render enqueued stylesheets
        Blade::directive('styles', function () {
            return "<?php echo app('asset.manager')->renderStyles(); ?>";
        });

        // @scripts - Render enqueued scripts
        Blade::directive('scripts', function ($expression) {
            $inFooter = $expression ?: 'false';
            return "<?php echo app('asset.manager')->renderScripts($inFooter); ?>";
        });

        // @externalScript - Enqueue and render a script
        Blade::directive('externalScript', function ($expression) {
            return "<?php app('asset.manager')->enqueueScript($expression); ?>";
        });

        // @externalStyle - Enqueue and render a stylesheet
        Blade::directive('externalStyle', function ($expression) {
            return "<?php app('asset.manager')->enqueueStyle($expression); ?>";
        });
    }
}
