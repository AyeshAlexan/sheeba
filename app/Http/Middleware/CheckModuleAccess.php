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
                    return $this->deny($request, config('modules')[$moduleKey] ?? $moduleKey);
                }
                break;
            }
        }

        // Action-level check (save/add/edit/delete/print on a specific
        // route) — independent of the whole-page check above, since a
        // route here may not even be listed in module_routes (e.g. an
        // ajax save endpoint for a page the role can otherwise view).
        $actionRoutes = config('action_routes', []);

        foreach ($actionRoutes as $key => $routeNames) {
            if (in_array($routeName, $routeNames, true)) {
                [$moduleKey, $action] = explode('.', $key, 2);
                if (!Permissions::canDo($moduleKey, $action)) {
                    return $this->deny($request, config('modules')[$moduleKey] ?? $moduleKey);
                }
            }
        }

        return $next($request);
    }

    /**
     * AJAX/JSON requests (almost every save/update/delete endpoint in this
     * app) get a JSON 403 their existing `.ajax({ error: ... })` handlers
     * can show sensibly; full-page navigations get the same "Access
     * Restricted" page used for whole-module denials.
     */
    private function deny(Request $request, string $moduleLabel)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'error',
                'message' => "Your role doesn't have permission to do this in {$moduleLabel}.",
            ], 403);
        }

        return response()->view('errors.no-access', [
            'moduleLabel' => $moduleLabel,
        ], 403);
    }
}
