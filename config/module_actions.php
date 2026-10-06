<?php

// Per-module action keys controllable from the Role Permissions screen,
// layered on top of the module-level on/off switch in config/modules.php.
// A role must already have module-level access (role_permissions.is_enabled)
// before any of these can take effect — see App\Support\Permissions::canDo().
//
// Only list actions that actually exist as buttons on that module's pages,
// so the admin UI doesn't show meaningless checkboxes (e.g. Reports pages
// have no Add/Edit/Delete, just Print/export).
//
// 'role_permissions' is intentionally omitted — that module is just the
// permissions grid itself, already fully gated by module-level access.
return [
    'user_management' => ['add', 'edit', 'delete'],
    'system'          => ['add', 'edit', 'delete'],
    'master'          => ['add', 'edit', 'delete'],
    'stock'           => ['add', 'edit', 'delete', 'save', 'print'],
    'purchases'       => ['add', 'edit', 'delete', 'save', 'print'],
    'sales'           => ['add', 'edit', 'delete', 'save', 'print'],
    'vouchers'        => ['add', 'edit', 'delete', 'save', 'print'],
    'expense'         => ['add', 'edit', 'delete'],
    'banking'         => ['add', 'edit', 'delete'],
    'accounting'      => ['add', 'edit', 'delete'],
    'reports'         => ['print'],
];
