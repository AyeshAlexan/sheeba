<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Userrole;
use App\Models\RolePermission;

class RolePermissionController extends Controller
{
    public function index(Request $request)
    {
        $roles = Userrole::orderBy('role_name')->pluck('role_name');
        $modules = config('modules');

        $allPermissions = RolePermission::where('is_enabled', true)->get()->groupBy('role_name');

        // Every role's enabled modules, keyed by role name — the whole
        // dataset is small (a handful of roles × a dozen modules), so the
        // view can switch between roles instantly client-side instead of
        // reloading the page per tab.
        $enabledByRole = $roles->mapWithKeys(function ($role) use ($allPermissions) {
            $enabled = ($allPermissions->get($role) ?? collect())->pluck('module_key');
            return [$role => $enabled];
        });

        // Trim-tolerant match — some role names carry stray whitespace
        // (e.g. "Operator ") in the data.
        $requestedRole = trim((string) $request->input('role'));
        $initialRole = $roles->first(fn ($r) => trim($r) === $requestedRole) ?? $roles->first();

        return view('role_permissions', [
            'roles'         => $roles,
            'modules'       => $modules,
            'enabledByRole' => $enabledByRole,
            'initialRole'   => $initialRole,
        ]);
    }

    public function save(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string',
            'modules'   => 'array',
            'modules.*' => 'string',
        ]);

        $roleName    = $request->role_name;
        $allModules  = array_keys(config('modules'));
        $enabledKeys = $request->input('modules', []);

        foreach ($allModules as $moduleKey) {
            RolePermission::updateOrCreate(
                ['role_name' => $roleName, 'module_key' => $moduleKey],
                ['is_enabled' => in_array($moduleKey, $enabledKeys)]
            );
        }

        return response()->json(['status' => 'success']);
    }
}
