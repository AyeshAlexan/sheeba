<?php

namespace App\Support;

use App\Models\RolePermission;
use App\Models\RoleModuleAction;
use App\Models\RoleReportPermission;

class Permissions
{
    /**
     * Whether the logged-in user's role has a given module turned on.
     * Every role, including Admin, is governed by the role_permissions
     * table — access is opt-in, not opt-out. Any role/module combination
     * with no matching row (including roles that don't exist in
     * role_permissions at all) is denied.
     */
    public static function can(string $moduleKey): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        $role = trim((string) $user->role);

        // Role names in the userroles table sometimes carry stray
        // whitespace (e.g. "Operator "); compare trimmed on both sides
        // rather than relying on the data being clean.
        return RolePermission::whereRaw('TRIM(role_name) = ?', [$role])
            ->where('module_key', $moduleKey)
            ->where('is_enabled', true)
            ->exists();
    }

    /**
     * Whether the logged-in user's role can perform a specific action
     * (add/edit/delete/save/print) within a module. Requires module-level
     * access first — a role with the module switched off can never do
     * anything inside it regardless of what's saved here. Same opt-in
     * philosophy as can(): no matching row means denied, Admin included.
     */
    public static function canDo(string $moduleKey, string $action): bool
    {
        if (!self::can($moduleKey)) {
            return false;
        }

        $user = auth()->user();
        $role = trim((string) $user->role);

        return RoleModuleAction::whereRaw('TRIM(role_name) = ?', [$role])
            ->where('module_key', $moduleKey)
            ->where('action_key', $action)
            ->where('is_enabled', true)
            ->exists();
    }

    /**
     * Whether the logged-in user's role can see a specific report. The
     * Reports module switch (can('reports')) still acts as the master
     * on/off — a role with Reports disabled never sees any report
     * regardless of individual report ticks. Within a role that has
     * Reports enabled, role_report_permissions narrows access down to
     * specific reports; a role with no rows saved there at all falls
     * back to full access (matches pre-existing behaviour, so this never
     * silently locks out roles that haven't had the new per-report
     * toggles on the Role Permissions page touched yet).
     */
    public static function canViewReport(string $reportKey): bool
    {
        if (!self::can('reports')) {
            return false;
        }

        $role = trim((string) optional(auth()->user())->role);

        if ($role === '') {
            return false;
        }

        $hasAnyOverride = RoleReportPermission::whereRaw('TRIM(role_name) = ?', [$role])->exists();

        if (!$hasAnyOverride) {
            return true;
        }

        return RoleReportPermission::whereRaw('TRIM(role_name) = ?', [$role])
            ->where('report_key', $reportKey)
            ->where('is_enabled', true)
            ->exists();
    }
}
