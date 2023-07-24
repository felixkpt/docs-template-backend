<?php

namespace App\Services\NestedRoutes\Http\Middleware;

use Closure;
use Illuminate\Routing\Router;

class NestedRoutesAuth
{
    protected $router;
    public function __construct(Router $router)
    {
      $this->router = $router;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // $role_repo = new RoleRepository($request);
        // $role_repo->check();
        return $next($request);
    }
}
