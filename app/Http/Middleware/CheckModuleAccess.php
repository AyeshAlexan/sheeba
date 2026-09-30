<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Support\Permissions;

class CheckModuleAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $routeName = optional($request->route())->getName();

        if (!$routeName) {
            return $next($request);
        }

        $moduleRoutes = config('module_routes', []);

        foreach ($moduleRoutes as $moduleKey => $routeNames) {
            if (in_array($routeName, $routeNames, true)) {
                if (!Permissions::can($moduleKey)) {
                    return response()->view('errors.no-access', [
                        'moduleLabel' => config('modules')[$moduleKey] ?? $moduleKey,
                    ], 403);
                }
                break;
            }
        }

        return $next($request);
    }
}
