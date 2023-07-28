<?php

namespace App\Services\NestedRoutes\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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


    if ($request) {
      $this->request = $request;
      $this->user = auth()->user();
      // For testing purposes only enable the below user is you accessing directly from browser 
      // $this->user = User::first();
      $this->path = Route::getFacadeRoot()->current()->uri();
    }

    $this->check();
    return $next($request);
  }

  public function check()
  {

    return true;
    // $this->user = null;

    $current = request()->getPathInfo();

    if (Str::startsWith($current, '/api/client')) {
      return true;
    } else if ($this->user) {
      return $this->authorize($current);
    } else {
      App::abort(401, "Not authorized to access this page/resource/endpoint");
    }
  }

  protected function authorize($current)
  {

    $allowed_urls = [];
    $allowed_urls[] = '/';
    $allowed_urls[] = '';
    $allowed_urls[] = 'auth/user';
    $allowed_urls[] = 'auth/password';

    if (in_array($current, $allowed_urls)) {
      return true;
    }


    $user = $this->user;
    // Direct permissions
    // $permissions = $user->getDirectPermissions(); // Or $user->permissions;

    // Permissions inherited from the user's roles
    $permissions = $user->getPermissionsViaRoles()->pluck('uri');

    // All permissions which apply on the user (inherited and direct)
    // $user->getAllPermissions();


    $incoming_route = Str::after(Route::getCurrentRoute()->uri, 'api/');
    $method = request()->method();

    $found_path = '';
    foreach ($permissions as $uri) {

      $res = preg_split('#@#', $uri, 2);
      $curr_route = Str::startsWith($res[0], 'admin') ? $res[0] : 'admin/' . $res[0];
      $methods = array_filter(explode('@', str_replace('|', '', $res[1] ?? '')));

      // For testing purposes all methods allow GET
      $methods = [...$methods, 'GET'];

      // dump($incoming_route . '  <----->  ' . $curr_route, $methods);

      if ($incoming_route == $curr_route) {
        $found_path = true;
        if (in_array($method, $methods)) {
          return true;
        }
      }
    }

    if ($found_path ===  true)
      $this->unauthorize(405);

    return $this->unauthorize();
  }

  public function unauthorize($status = 403, $message = null)
  {
    $common_paths = ['logout', 'login', 'register'];
    $path = $this->path;
    if (!in_array($path, $common_paths)) {
      App::abort($status, ($status === 405 ? "Not authorized to perform current method on" : "Not authorized to access") . " this page/resource/endpoint");
    }
  }
}
