<?php

namespace App\Services\NestedRoutes\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class NestedRoutesAuth
{

    protected $router;

    protected $path;
    protected $user;
    protected $menus;
    protected $allow = false;
    protected $request;
    protected $is_app = 0;
    protected $common;
    protected $userPermissions;
    protected $allPermissionsFile;

    protected $allowedPermissions;
    protected $role;
    protected $urls = [];
    protected $loopLevel = 0;

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
        // App::abort(403, "Not authorized to access this page/resource/endpoint");

        // Set up necessary data for authorization checks...
        if ($request) {
            $this->request = $request;
            $this->user = auth()->user();

            // For testing purposes only enable the below user if you are accessing directly from the browser
            // $this->user = User::first();

            $this->path = Route::getFacadeRoot()->current()->uri();
        }

        // Perform checks for authorization...
        $this->check();
        return $next($request);
    }

    /**
     * Perform authorization checks for the incoming request.
     *
     * @return mixed
     */
    public function check()
    {
        // Dummy implementation, change as per your requirements...
        // return true;
        // $this->user = null;

        $current = rtrim(request()->getPathInfo(), '/');

        // Allow certain routes without authorization...
        if (Str::startsWith($current, '/api/client')) {
            return true;
        } elseif ($this->user) {
            return $this->authorize($current);
        } else {
            App::abort(401, "Not authorized to access this page/resource/endpoint");
        }
    }

    /**
     * Perform authorization checks for the current user.
     *
     * @param  string  $current
     * @return mixed
     */
    protected function authorize($currentRoute)
    {

        // Define routes that are allowed without specific permissions...
        $allowedRoutes = [
            '/',
            'auth/user',
            'auth/password',
            '/api/admin/settings/role-permissions/roles/get-user-roles-and-direct-permissions',
            '/api/admin/settings/role-permissions/roles/role/{id}/get-role-menu',
            '/api/admin/settings/role-permissions/roles/role/{id}/get-user-route-permissions',
            '/api/admin/file-repo/*',
        ];

        // Check if the current route matches any of the allowed routes
        $allowed = collect($allowedRoutes)->contains(function ($allowedRoute) use ($currentRoute) {

            if (Str::endsWith($allowedRoute, '*') && Str::startsWith($currentRoute, Str::replaceLast('*', '', $allowedRoute))) return true;

            return preg_match("#^" . str_replace(['/', '{id}'], ['\/', '\d+'], $allowedRoute) . "$#", $currentRoute);
        });

        if ($allowed) return true;

        // Retrieve permissions associated with the user...
        $user = $this->user;
        // Direct permissions
        // $permissions = $user->getDirectPermissions(); // Or $user->permissions;

        // if ($user->email !== 'admin@example.com') {
        // return true;
        // }

        // Permissions inherited from the user's roles
        $permissions = $user->getPermissionsViaRoles()->pluck('uri');

        // dd($user->email, $user->getRoleNames(),$permissions);

        // All permissions which apply to the user (inherited and direct)
        // $user->getAllPermissions();

        // Get the current route and request method...
        $incoming_route = Str::after(Route::getCurrentRoute()->uri, 'api/');
        $method = request()->method();

        $found_path = '';
        foreach ($permissions as $uri) {
            // Split the URI into route and methods...
            $res = preg_split('#@#', $uri, 2);
            $curr_route = Str::startsWith($res[0], 'admin') ? $res[0] : 'admin/' . $res[0];

            $methods = array_filter(explode('@', str_replace('|', '', $res[1] ?? '')));

            // For testing purposes, allow all methods to access the route...
            $methods = [...$methods, 'GET'];

            // Check if the current route and method match the user's permissions...
            if ($incoming_route == $curr_route) {
                $found_path = true;
                if (in_array($method, $methods)) {
                    return true;
                }
            }
        }

        // If the route is found but the method is not allowed...
        if ($found_path === true) {
            $this->unauthorize(405);
        }

        return $this->unauthorize();
    }

    /**
     * Abort the request with an unauthorized status and message.
     *
     * @param  int  $status
     * @param  string|null  $message
     * @return void
     */
    public function unauthorize($status = 403, $message = null)
    {
        // Define common paths that do not require authorization checks...
        $common_paths = ['logout', 'login', 'register'];
        $path = $this->path;
        if (!in_array($path, $common_paths)) {
            App::abort($status, ($status === 405 ? "Not authorized to perform the current method on" : "Not authorized to access") . " this page/resource/endpoint");
        }
    }
}
