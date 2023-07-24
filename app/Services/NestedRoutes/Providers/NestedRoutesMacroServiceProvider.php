<?php

namespace App\Services\NestedRoutes\Providers;

use Exception;
use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;

class NestedRoutesMacroServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Define the custom macro for the Route class
        Route::macro('isHidden', function ($value = true) {
            $this->hidden = $value;
            return $this;
        });

        Route::macro('isHiddenRoute', function () {
            try {
                return $this->hidden ?? false;
            } catch (Exception $e) {
                return false;
            }
        });
    }
}
