<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Userrole;
use App\Models\RolePermission;
use App\Models\RoleModuleAction;
use App\Models\RoleReportPermission;

class RolePermissionController extends Controller
{
    public function index(Request $request)
    {
        $roles = Userrole::orderBy('role_name')->pluck('role_name');
        $modules = config('modules');
        $moduleActions = config('module_actions');

        $allPermissions = RolePermission::where('is_enabled', true)->get()->groupBy('role_name');
        $allActions = RoleModuleAction::where('is_enabled', true)->get()->groupBy('role_name');

        // Every role's enabled modules, keyed by role name — the whole
        // dataset is small (a handful of roles × a dozen modules), so the
        // view can switch between roles instantly client-side instead of
        // reloading the page per tab.
        $enabledByRole = $roles->mapWithKeys(function ($role) use ($allPermissions) {
            $enabled = ($allPermissions->get($role) ?? collect())->pluck('module_key');
            return [$role => $enabled];
        });

        // Every role's enabled actions, keyed by role name then module key —
        // same small-dataset reasoning as above.
        $enabledActionsByRole = $roles->mapWithKeys(function ($role) use ($allActions) {
            $enabled = ($allActions->get($role) ?? collect())
                ->groupBy('module_key')
                ->map(fn ($rows) => $rows->pluck('action_key'));
            return [$role => $enabled];
        });

        // Trim-tolerant match — some role names carry stray whitespace
        // (e.g. "Operator ") in the data.
        $requestedRole = trim((string) $request->input('role'));
        $initialRole = $roles->first(fn ($r) => trim($r) === $requestedRole) ?? $roles->first();

        $reportGroups = config('report_labels');
        $allReportKeys = collect($reportGroups)->flatMap(fn ($reports) => array_keys($reports));

        $allReportPermissions = RoleReportPermission::get()->groupBy('role_name');

        // Same default-allow philosophy as the Reports module's per-report
        // gate in Permissions::canViewReport() — a role with no saved rows
        // here yet shows every report pre-checked, matching the access it
        // already has, instead of looking like everything was just denied.
        $enabledReportsByRole = $roles->mapWithKeys(function ($role) use ($allReportPermissions, $allReportKeys) {
            $saved = ($allReportPermissions->get($role) ?? collect())->keyBy('report_key');

            if ($saved->isEmpty()) {
                return [$role => $allReportKeys];
            }

            $enabled = $allReportKeys->filter(function ($key) use ($saved) {
                return $saved->has($key) ? (bool) $saved->get($key)->is_enabled : true;
            })->values();

            return [$role => $enabled];
        });

        return view('role_permissions', [
            'roles'                => $roles,
            'modules'              => $modules,
            'moduleActions'        => $moduleActions,
            'enabledByRole'        => $enabledByRole,
            'enabledActionsByRole' => $enabledActionsByRole,
            'initialRole'          => $initialRole,
            'reportGroups'         => $reportGroups,
            'enabledReportsByRole' => $enabledReportsByRole,
        ]);
    }

    public function save(Request $request)
    {
        $request->validate([
            'role_name'   => 'required|string',
            'modules'     => 'array',
            'modules.*'   => 'string',
            'actions'     => 'array',
            'actions.*'   => 'array',
            'actions.*.*' => 'string',
        ]);

        $roleName     = $request->role_name;
        $allModules   = array_keys(config('modules'));
        $moduleActions = config('module_actions');
        $enabledKeys  = $request->input('modules', []);
        $enabledActions = $request->input('actions', []);

        foreach ($allModules as $moduleKey) {
            RolePermission::updateOrCreate(
                ['role_name' => $roleName, 'module_key' => $moduleKey],
                ['is_enabled' => in_array($moduleKey, $enabledKeys)]
            );
        }

        foreach ($moduleActions as $moduleKey => $actions) {
            $enabledForModule = $enabledActions[$moduleKey] ?? [];
            foreach ($actions as $actionKey) {
                RoleModuleAction::updateOrCreate(
                    ['role_name' => $roleName, 'module_key' => $moduleKey, 'action_key' => $actionKey],
                    ['is_enabled' => in_array($actionKey, $enabledForModule)]
                );
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function saveReports(Request $request)
    {
        $request->validate([
            'role_name'  => 'required|string',
            'reports'    => 'array',
            'reports.*'  => 'string',
        ]);

        $roleName    = $request->role_name;
        $enabledKeys = $request->input('reports', []);
        $allKeys     = collect(config('report_labels'))->flatMap(fn ($reports) => array_keys($reports));

        foreach ($allKeys as $reportKey) {
            RoleReportPermission::updateOrCreate(
                ['role_name' => $roleName, 'report_key' => $reportKey],
                ['is_enabled' => in_array($reportKey, $enabledKeys, true)]
            );
        }

        return response()->json(['status' => 'success']);
    }
}
