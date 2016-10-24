<?php

namespace Dpovshed\Zipus\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Dpovshed\Zipus\Console\Commands\ZipusImport;
use Dpovshed\Zipus\ZipCityLookup;

class ZipServiceProvider extends ServiceProvider
{
    /**
     * Create a new service provider instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     */
    public function __construct($app)
    {
        $this->defer = !config('app.debug');
        parent::__construct($app);
    }

    /**
     * Boot and configure the application paths
     *
     * @return void
     */
    public function boot()
    {
        $this->setupRoutes($this->app->router);

        // Register commands
        $this->commands('command.zipus.import');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('zipcode', function ($app) {
            return new ZipCityLookup;
        });

        $this->app->singleton('command.zipus.import', function ($app) {
            return new ZipusImport;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['zipcode', 'command.zipus.import'];
    }


    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    protected function setupRoutes(Router $router)
    {
        $router->group(
            ['namespace' => 'Dpovshed\Zipus\Http\Controllers'],
            function ($router) {
                include __DIR__.'/../Http/routes.php';
            }
        );
    }

}
