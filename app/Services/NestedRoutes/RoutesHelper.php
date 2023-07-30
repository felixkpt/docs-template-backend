<?php

namespace App\Services\NestedRoutes;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class RoutesHelper
{
    /**
     * Get nested routes from a specific folder.
     *
     * @param string $folder The folder name.
     * @return array An array containing information about the nested routes.
     */
    public static function getRoutes($nested_routes_folder, $leftTrim)
    {
        $nestedRoutes = [];

        $routes_path = base_path('routes/' . $nested_routes_folder);

        if (file_exists($routes_path)) {
            // Filter out the driver.php files and process each route file
            $route_files = collect(File::allFiles($routes_path))->filter(fn ($file) => !Str::is($file->getFileName(), 'driver.php'));

            foreach ($route_files as $file) {

                // Handle the route file and extract relevant information
                $res = self::handle($file, $nested_routes_folder, $routes_path, $leftTrim);

                $prefix = $res['prefix'];
                $file_path = $res['file_path'];
                $folder_after_nested = $res['folder_after_nested'];

                // Get the existing routes before adding new ones
                $existingRoutes = collect(Route::getRoutes())->pluck('uri');

                Route::group(['prefix' => $prefix], function () use ($file_path, $existingRoutes, $folder_after_nested, &$nestedRoutes, $routes_path) {

                    require $file_path;

                    // Get the newly added routes and their corresponding folders
                    $routes = collect(Route::getRoutes())->filter(function ($route) use ($existingRoutes) {
                        return !in_array($route->uri, $existingRoutes->toArray());
                    })->map(function ($route) use ($folder_after_nested) {
                        $uri = $route->uri;

                        $methods = '@' . implode('|@', $route->methods());
                        $uri_methods = $uri . $methods;

                        $slug = Str::slug(Str::replace('/', '.', $uri), '.');

                        $parts = explode('/', $uri);
                        $name = end($parts);

                        if (isset($route->action['controller'])) {
                            $c = explode('@', $route->action['controller']);
                            if (count($c) === 2) {
                                $name = $c[1];
                            }
                        }

                        if ($route->getName()) {
                            $parts = explode('.', $route->getName());
                            $name = end($parts);
                        }

                        $name = Str::title($name);

                        return [
                            'uri' => $uri,
                            'methods' => $methods,
                            'uri_methods' => $uri_methods,
                            'slug' => $slug,
                            'title' => $name,
                            'folder' => $folder_after_nested,
                            'hidden' => $route->hiddenRoute(),
                            'icon' => $route->getIcon()
                        ];
                    });

                    $nestedRoutes = array_merge($nestedRoutes, $routes->toArray());
                });
            }
        }

        return $nestedRoutes;
    }

    /**
     * Handle the processing of a route file and extract relevant information.
     *
     * @param \SplFileInfo $file The route file.
     * @param string $nested_routes_folder The folder name after 'nested-routes'.
     * @param string $routes_path The base path to the routes folder.
     * @param bool $get_folder_after_nested Whether to get the folder name after the 'nested-routes' folder.
     * @return array The processed route information as an associative array.
     */
    static function handle($file, $nested_routes_folder, $routes_path, $get_folder_after = null)
    {
        $path = $file->getPath();

        $folder_after_nested = null;
        if ($get_folder_after)
            $folder_after_nested = self::getFolderAfterNested($path, $nested_routes_folder);

        $file_name = $file->getFileName();
        $prefix = str_replace($file_name, '', $path);
        $prefix = str_replace($routes_path, '', $prefix);
        $file_path = $file->getPathName();
        $arr = explode('/', $prefix);
        $len = count($arr);
        $main_file = $arr[$len - 1];
        $arr = array_map('ucwords', $arr);
        $arr = array_filter($arr);
        $ext_route = str_replace('user.route.php', '', $file_name);
        $ext_route = str_replace('index.route.php', '', $file_name);
        if ($main_file . '.route.php' === $ext_route)
            $ext_route = str_replace($main_file . '.', '.', $ext_route);
        $ext_route = str_replace('.route.php', '', $ext_route);
        if ($ext_route)
            $ext_route = '/' . $ext_route;
        $prefix = strtolower($prefix . $ext_route);

        $res = [
            'prefix' => $prefix ?: '/' . $folder_after_nested,
            'file_path' => $file_path,
            'folder_after_nested' => $folder_after_nested,
        ];

        // if ($get_folder_after)
        //     dump($res);

        return $res;
    }

    /**
     * Get the folder name after the nested-routes folder.
     *
     * @param string $path The full path to the route file.
     * @param string $nested_routes_folder The folder name after 'nested-routes'.
     * @return string|null The folder name after 'nested-routes', or null if not found.
     */
    static function getFolderAfterNested($path, $nested_routes_folder)
    {
        $parts = explode('/', $nested_routes_folder);
        $folder_after_nested = null;

        $nested_routes_folder = trim($nested_routes_folder, '/');

        $start_position = strpos($path, $nested_routes_folder);

        if ($start_position !== false) {
            $start_position += strlen($nested_routes_folder) + 1; // Adding 1 to skip the slash after the folder name.
            $folder_after_nested = substr($path, $start_position);
        }

        // Loop through all parts of $nested_routes_folder and handle empty parts
        foreach ($parts as $part) {
            if (!empty($part)) {
                $folder_after_nested = str_replace($part, '', $folder_after_nested, $count);
                if ($count > 0) {
                    break;
                }
            }
        }

        if (!$folder_after_nested) $folder_after_nested = $part;

        return $folder_after_nested;
    }
}
