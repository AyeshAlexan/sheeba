<?php

namespace App\Support;

use App\Models\RolePermission;

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
}
