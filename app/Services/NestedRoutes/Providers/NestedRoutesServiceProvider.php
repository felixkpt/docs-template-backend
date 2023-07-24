<?php

namespace App\Services\NestedRoutes\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class NestedRoutesServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('nested_routes_auth', \App\Services\NestedRoutes\Http\Middleware\NestedRoutesAuth::class);
        $this->loadRoutesFrom(base_path('routes/'.config('nested_routes.admin_folder').'/driver.php'));
    }
}
