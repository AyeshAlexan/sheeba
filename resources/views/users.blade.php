@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Users</title>
</head>

<style>
    :root {
        /* Retheme note: values only, mapped to the same blue/navy system
           used across the navbar, sidebar, and dashboard. */
        --navy:         #14213D;
        --navy-mid:     #1B2B4D;
        --blue:         #1677FF;
        --blue-mid:     #0F68E0;
        --blue-light:   #5A9DFF;
        --cyan:         #14213D;
        --grad-header:  linear-gradient(135deg, #14213D 0%, #1677FF 100%);
        --grad-btn:     linear-gradient(135deg, #1677FF, #14213D);
        --grad-accent:  linear-gradient(135deg, #14213D, #1677FF);
        --white:        #ffffff;
        --surface:      #F6F8FC;
        --card:         #ffffff;
        --border:       #E5EAF2;
        --text-main:    #172033;
        --text-sub:     #667085;
        --text-light:   #98A2B3;
        --green:        #16A34A;
        --green-light:  #EAFBEF;
        --amber:        #F59E0B;
        --amber-light:  #FEF6E7;
        --red:          #EF4444;
        --red-light:    #FEECEC;
        --primary-light:#EAF3FF;
        --radius:       14px;
        --radius-sm:    9px;
        --shadow:       0 2px 8px rgba(20,33,61,.06), 0 1px 3px rgba(20,33,61,.04);
        --shadow-card:  0 4px 20px rgba(20,33,61,.05);
        --shadow-lg:    0 12px 40px rgba(20,33,61,.12);
        --font:         'Inter', sans-serif;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { background: var(--surface); color: var(--text-main); }

    .pu-wrap { padding: 24px 28px; }

    /* Banner */
    .pu-banner {
        background: var(--grad-header); border-radius: var(--radius);
        padding: 22px 28px; display: flex; align-items: center;
        justify-content: space-between; margin-bottom: 22px;
        flex-wrap: wrap; gap: 14px; box-shadow: var(--shadow-card);
        position: relative; overflow: hidden;
    }
    .pu-banner::before {
        content: ''; position: absolute; top: -40px; right: -40px;
        width: 160px; height: 160px; border-radius: 50%;
        background: rgba(255,255,255,.06);
    }
    .pu-banner::after {
        content: ''; position: absolute; bottom: -30px; right: 120px;
        width: 100px; height: 100px; border-radius: 50%;
        background: rgba(255,255,255,.04);
    }
    .pu-banner-icon {
        width: 46px; height: 46px; border-radius: 12px;
        background: rgba(255,255,255,.18); display: flex;
        align-items: center; justify-content: center;
        font-size: 1.3rem; flex-shrink: 0; margin-right: 14px;
    }
    .pu-banner-text h2 { font-size: 1.2rem; font-weight: 700; color: #fff; }
    .pu-banner-text p  { font-size: .82rem; color: rgba(255,255,255,.75); margin-top: 2px; }
    .pu-banner-left    { display: flex; align-items: center; }
    .pu-banner-actions { display: flex; gap: 10px; position: relative; z-index: 1; }

    /* Buttons */
    .btn-white {
        display: inline-flex; align-items: center; gap: 7px;
        background: var(--white); color: var(--blue); border: none;
        border-radius: var(--radius-sm); padding: 9px 18px;
        font-family: var(--font); font-size: .83rem; font-weight: 600;
        cursor: pointer; transition: all .2s; text-decoration: none;
        box-shadow: 0 2px 8px rgba(0,0,0,.12);
    }
    .btn-white:hover { background: var(--primary-light); color: var(--navy); transform: translateY(-1px); }

    .btn-ghost {
        display: inline-flex; align-items: center; gap: 7px;
        background: rgba(255,255,255,.15); color: #fff;
        border: 1.5px solid rgba(255,255,255,.35); border-radius: var(--radius-sm);
        padding: 8px 17px; font-family: var(--font); font-size: .83rem; font-weight: 600;
        cursor: pointer; transition: all .2s;
    }
    .btn-ghost:hover { background: rgba(255,255,255,.25); border-color: rgba(255,255,255,.6); }

    .btn-outline {
        display: inline-flex; align-items: center; gap: 6px;
        background: transparent; color: var(--text-sub);
        border: 1.5px solid var(--border); border-radius: var(--radius-sm);
        padding: 8px 16px; font-family: var(--font); font-size: .83rem; font-weight: 500;
        cursor: pointer; transition: all .2s;
    }
    .btn-outline:hover { border-color: var(--blue-mid); color: var(--blue); }

    .btn-blue {
        display: inline-flex; align-items: center; gap: 7px;
        background: var(--grad-btn); color: #fff; border: none;
        border-radius: var(--radius-sm); padding: 9px 20px;
        font-family: var(--font); font-size: .83rem; font-weight: 600;
        cursor: pointer; transition: all .2s;
        box-shadow: 0 3px 10px rgba(21,101,192,.3);
    }
    .btn-blue:hover { box-shadow: 0 5px 16px rgba(21,101,192,.4); transform: translateY(-1px); }

    .btn-amber {
        display: inline-flex; align-items: center; gap: 7px;
        background: var(--amber); color: #fff; border: none;
        border-radius: var(--radius-sm); padding: 9px 20px;
        font-family: var(--font); font-size: .83rem; font-weight: 600;
        cursor: pointer; transition: all .2s;
        box-shadow: 0 3px 10px rgba(245,158,11,.3);
    }
    .btn-amber:hover { transform: translateY(-1px); background: #D97F09; box-shadow: 0 5px 16px rgba(245,158,11,.4); }

    /* Stats */
    .pu-stats { display: flex; gap: 14px; margin-bottom: 22px; flex-wrap: wrap; }
    .stat-pill {
        flex: 1; min-width: 130px; background: var(--card);
        border: 1.5px solid var(--border); border-radius: var(--radius);
        padding: 14px 18px; display: flex; align-items: center; gap: 12px;
        box-shadow: var(--shadow); transition: box-shadow .2s;
    }
    .stat-pill:hover { box-shadow: var(--shadow-card); }
    .stat-dot {
        width: 38px; height: 38px; border-radius: 9px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 1rem;
    }
    .sd-blue  { background: var(--primary-light); }
    .sd-green { background: var(--green-light); }
    .sd-amber { background: var(--amber-light); }
    .stat-num { font-size: 1.4rem; font-weight: 700; color: var(--navy); line-height: 1; }
    .stat-lbl { font-size: .75rem; color: var(--text-sub); margin-top: 2px; font-weight: 500; }

    /* Alerts */
    .pu-alert {
        border-radius: var(--radius-sm); padding: 11px 16px; font-size: .84rem;
        font-weight: 500; margin-bottom: 16px; display: flex; align-items: center;
        gap: 8px; border-left: 4px solid;
    }
    .pu-alert-success { background: var(--green-light);  color: var(--green); border-color: #4caf50; }
    .pu-alert-danger  { background: var(--red-light);    color: var(--red);   border-color: #ef5350; }

    /* Card */
    .pu-card {
        background: var(--card); border-radius: var(--radius);
        border: 1.5px solid var(--border); box-shadow: var(--shadow-card); overflow: hidden;
    }
    .pu-toolbar {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 20px; border-bottom: 1.5px solid var(--border);
        gap: 12px; flex-wrap: wrap; background: #fafbff;
    }
    .toolbar-title {
        font-size: .9rem; font-weight: 700; color: var(--navy);
        display: flex; align-items: center; gap: 8px;
    }
    .toolbar-badge {
        background: var(--primary-light); color: var(--blue);
        font-size: .7rem; font-weight: 700; padding: 2px 8px;
        border-radius: 20px;
    }
    .search-box { position: relative; }
    .search-box input {
        background: var(--surface); border: 1.5px solid var(--border);
        border-radius: var(--radius-sm); padding: 8px 13px 8px 35px;
        font-family: var(--font); font-size: .83rem; width: 230px;
        outline: none; color: var(--text-main); transition: border-color .2s;
    }
    .search-box input:focus { border-color: var(--blue-mid); background: #fff; }
    .search-box input::placeholder { color: var(--text-light); }
    .search-box svg { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--text-sub); }

    /* Table */
    .table-scroll { overflow-x: auto; }
    .pu-table { width: 100%; border-collapse: collapse; }
    .pu-table thead tr { background: linear-gradient(90deg, #14213D 0%, #1677FF 100%); }
    .pu-table thead th {
        padding: 12px 16px; text-align: left; font-size: .72rem;
        font-weight: 600; color: rgba(255,255,255,.9);
        text-transform: uppercase; letter-spacing: .7px; white-space: nowrap;
    }
    .pu-table thead th:first-child { padding-left: 20px; }
    .pu-table tbody tr { border-bottom: 1px solid var(--border); transition: background .15s; }
    .pu-table tbody tr:last-child { border-bottom: none; }
    .pu-table tbody tr:hover { background: #f0f4ff; }
    .pu-table tbody td { padding: 12px 16px; font-size: .855rem; vertical-align: middle; }
    .pu-table tbody td:first-child { padding-left: 20px; }

    .user-cell { display: flex; align-items: center; gap: 11px; }
    .u-avatar {
        width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
        background: linear-gradient(135deg, #1677FF, #14213D);
        color: #fff; display: flex; align-items: center; justify-content: center;
        font-size: .75rem; font-weight: 700; letter-spacing: .5px;
    }
    .u-name     { font-weight: 600; font-size: .875rem; color: var(--text-main); }
    .u-username { font-size: .75rem; color: var(--text-sub); margin-top: 1px; }

    .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: .71rem; font-weight: 600; white-space: nowrap; }
    .badge-role   { background: var(--green-light); color: var(--green); }
    .badge-branch { background: var(--amber-light);  color: #7c4300; }
    .badge-bc     { background: var(--primary-light); color: var(--navy); letter-spacing: .5px; }

    .act-wrap { display: flex; gap: 5px; flex-wrap: wrap; }
    .act-btn {
        display: inline-flex; align-items: center; gap: 4px;
        border: none; border-radius: 5px; padding: 5px 11px;
        font-family: var(--font); font-size: .75rem; font-weight: 600;
        cursor: pointer; transition: opacity .15s, transform .12s;
        text-decoration: none; white-space: nowrap;
    }
    .act-btn:hover { opacity: .85; transform: translateY(-1px); }
    .act-edit   { background: var(--green-light);   color: var(--green); }
    .act-view   { background: var(--primary-light); color: var(--blue); }
    .act-delete { background: var(--red-light);     color: var(--red); }

    .empty-row td { text-align: center; padding: 48px; color: var(--text-light); font-size: .9rem; }
    .pu-foot { padding: 12px 20px; border-top: 1.5px solid var(--border); background: #fafbff; }
    .pu-foot .pagination { margin: 0; }

    /* Modals */
    .modal-bg {
        display: none; position: fixed; inset: 0; z-index: 1055;
        background: rgba(10,20,60,.45); backdrop-filter: blur(4px);
        align-items: center; justify-content: center; padding: 20px;
    }
    .modal-bg.open { display: flex; }
    .modal-box {
        background: var(--card); border-radius: 14px; box-shadow: var(--shadow-lg);
        width: 100%; max-width: 660px; max-height: 92vh; overflow-y: auto;
        animation: popIn .2s ease;
    }
    .modal-box-lg { max-width: 780px; }
    @keyframes popIn {
        from { opacity: 0; transform: translateY(22px) scale(.96); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    .modal-head {
        background: var(--grad-accent); border-radius: 14px 14px 0 0;
        padding: 18px 22px; display: flex; align-items: center; justify-content: space-between;
    }
    .modal-head h4 {
        font-size: 1rem; font-weight: 700; color: #fff;
        display: flex; align-items: center; gap: 9px; margin: 0;
    }
    .mh-icon {
        width: 30px; height: 30px; border-radius: 7px;
        background: rgba(255,255,255,.2); display: flex; align-items: center;
        justify-content: center; font-size: .9rem;
    }
    .btn-close-x {
        width: 30px; height: 30px; border-radius: 7px; border: none;
        background: rgba(255,255,255,.15); color: rgba(255,255,255,.9);
        cursor: pointer; font-size: 1rem;
        display: flex; align-items: center; justify-content: center;
        transition: background .2s;
    }
    .btn-close-x:hover { background: rgba(255,255,255,.3); }
    .modal-body { padding: 22px 24px; }
    .modal-foot {
        padding: 14px 22px; border-top: 1.5px solid var(--border);
        display: flex; justify-content: flex-end; gap: 10px;
        background: #fafbff; border-radius: 0 0 14px 14px;
    }

    /* Form grid */
    .fg    { display: grid; gap: 15px; }
    .fg-2  { grid-template-columns: 1fr 1fr; }
    @media(max-width:560px) { .fg-2 { grid-template-columns: 1fr; } }

    .f-section {
        grid-column: 1 / -1; font-size: .7rem; font-weight: 700;
        color: var(--blue); text-transform: uppercase; letter-spacing: .8px;
        border-bottom: 1.5px solid var(--primary-light); padding-bottom: 5px;
    }
    .f-field { display: flex; flex-direction: column; gap: 4px; }
    .f-field label {
        font-size: .76rem; font-weight: 600; color: var(--navy);
        text-transform: uppercase; letter-spacing: .4px;
    }
    .f-field label .req { color: var(--red); margin-left: 2px; }
    .f-field input,
    .f-field select {
        background: var(--surface); border: 1.5px solid var(--border);
        border-radius: var(--radius-sm); padding: 9px 12px;
        font-family: var(--font); font-size: .855rem; color: var(--text-main);
        transition: border-color .2s, box-shadow .2s; outline: none; width: 100%;
    }
    .f-field input:focus,
    .f-field select:focus {
        border-color: var(--blue-mid);
        box-shadow: 0 0 0 3px rgba(21,101,192,.12);
        background: #fff;
    }
    .f-field input::placeholder { color: var(--text-light); }
    .f-hint { font-size: .71rem; color: var(--text-sub); margin-top: 2px; }

    .err-box {
        display: none; background: var(--red-light); border: 1px solid #ef9a9a;
        border-radius: var(--radius-sm); padding: 10px 14px; margin-bottom: 14px;
    }
    .err-box.show { display: block; }
    .err-box span { display: block; font-size: .8rem; color: var(--red); font-weight: 500; }
</style>

<body>
<div class="main-wrapper">
<div class="page-wrapper">
<div class="content pu-wrap">

    {{-- ── Banner ── --}}
    <div class="pu-banner">
        <div class="pu-banner-left">
            <div class="pu-banner-icon">👥</div>
            <div class="pu-banner-text">
                <h2>User Management</h2>
                <p>Manage system users, assign roles and branch access</p>
            </div>
        </div>
        <div class="pu-banner-actions">
            <button class="btn-ghost" id="openRoleModal">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                Add Role
            </button>
            <button class="btn-white" id="openAddUserModal">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Add User
            </button>
        </div>
    </div>

    {{-- ── Alerts ── --}}
    @if(session('delete'))
        <div class="pu-alert pu-alert-danger">🗑 {{ session('delete') }}</div>
    @endif
    @if(session('added'))
        <div class="pu-alert pu-alert-success">✅ {{ session('added') }}</div>
    @endif
    @if(session('role_added'))
        <div class="pu-alert pu-alert-success">✅ {{ session('role_added') }}</div>
    @endif

    {{-- ── Stats ── --}}
    <div class="pu-stats">
        <div class="stat-pill">
            <div class="stat-dot sd-blue">👤</div>
            <div>
                <div class="stat-num">{{ $users->total() }}</div>
                <div class="stat-lbl">Total Users</div>
            </div>
        </div>
        <div class="stat-pill">
            <div class="stat-dot sd-green">🏷</div>
            <div>
                <div class="stat-num">{{ $Userrole->count() }}</div>
                <div class="stat-lbl">Roles Defined</div>
            </div>
        </div>
        <div class="stat-pill">
            <div class="stat-dot sd-amber">🏢</div>
            <div>
                <div class="stat-num">{{ $Branch->count() }}</div>
                <div class="stat-lbl">Branches</div>
            </div>
        </div>
    </div>

    {{-- ── Table card ── --}}
    <div class="pu-card">
        <div class="pu-toolbar">
            <span class="toolbar-title">
                All Users
                <span class="toolbar-badge">{{ $users->total() }} entries</span>
            </span>
            <div class="search-box">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                <input type="text" id="search" placeholder="Search users...">
            </div>
        </div>

        <div class="table-scroll">
            <div class="table-data">
                <table class="pu-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Branch</th>
                            <th>Branch Code</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $data)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="u-avatar">{{ strtoupper(substr($data->name, 0, 2)) }}</div>
                                    <div>
                                        {{-- ✅ BUG FIX: was @{{ which escaped Blade and printed literally --}}
                                        <div class="u-name">{{ $data->name }}</div>
                                        <div class="u-username">{{ $data->username }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-role">{{ $data->role }}</span></td>
                            <td><span class="badge badge-branch">{{ $data->Branch }}</span></td>
                            <td><span class="badge badge-bc">{{ $data->BC }}</span></td>
                            <td>
                                <div class="act-wrap">
                                    <a href="#" class="act-btn act-edit update_user_form"
                                        data-id="{{ $data->id }}"
                                        data-username="{{ $data->username }}"
                                        data-name="{{ $data->name }}"
                                        data-role="{{ $data->role }}"
                                        data-branch="{{ $data->Branch }}"
                                        data-bc="{{ $data->BC }}">
                                        ✏️ Edit
                                    </a>
                                    <button class="act-btn act-delete delete_user" data-id="{{ $data->id }}">
                                        🗑 Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr class="empty-row"><td colspan="5">No users found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pu-foot">{!! $users->links() !!}</div>
    </div>

</div>
</div>
</div>

{{-- ════ ADD USER MODAL ════ --}}
<div class="modal-bg" id="addUserBg">
    <div class="modal-box modal-box-lg">
        <div class="modal-head">
            <h4><div class="mh-icon">👤</div> Add New User</h4>
            <button class="btn-close-x" data-close="addUserBg">✕</button>
        </div>
        <div class="modal-body">
            <div class="err-box" id="addErrBox"></div>
            <form id="addUserForm">
                @csrf
                <div class="fg fg-2">
                    <div class="f-section">Account Information</div>
                    <div class="f-field">
                        <label>Username <span class="req">*</span></label>
                        <input type="text" name="user_name" id="add_user_name" placeholder="e.g. john_doe">
                    </div>
                    <div class="f-field">
                        <label>Full Name <span class="req">*</span></label>
                        <input type="text" name="name" id="add_name" placeholder="e.g. John Doe">
                    </div>
                    <div class="f-field">
                        <label>Email</label>
                        <input type="email" name="email" id="add_email" placeholder="user@example.com">
                    </div>
                    <div class="f-field">
                        <label>Password <span class="req">*</span></label>
                        <input type="password" name="password" id="add_password" placeholder="Min. 5 characters">
                        <span class="f-hint">Use letters, numbers and symbols for a strong password</span>
                    </div>
                    <div class="f-section">Role &amp; Branch Access</div>
                    <div class="f-field">
                        <label>Role <span class="req">*</span></label>
                        <select name="role" id="add_role">
                            <option value="">— Select Role —</option>
                            @foreach($Userrole as $role)
                            <option value="{{ $role->role_name }}">{{ $role->role_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="f-field">
                        <label>Branch <span class="req">*</span></label>
                        <select name="Branch" id="add_Branch">
                            <option value="">— Select Branch —</option>
                            @foreach($Branch as $b)
                            <option value="{{ $b->name }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="BC" id="add_BC">
                </div>
            </form>
        </div>
        <div class="modal-foot">
            <button class="btn-outline" data-close="addUserBg">Cancel</button>
            <button class="btn-blue" id="submitAddUser">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                Create User
            </button>
        </div>
    </div>
</div>

{{-- ════ EDIT USER MODAL ════ --}}
<div class="modal-bg" id="editUserBg">
    <div class="modal-box modal-box-lg">
        <div class="modal-head">
            <h4><div class="mh-icon">✏️</div> Edit User</h4>
            <button class="btn-close-x" data-close="editUserBg">✕</button>
        </div>
        <div class="modal-body">
            <div class="err-box" id="editErrBox"></div>
            <form id="updateUser">
                @csrf
                <input type="hidden" id="up_id">
                <div class="fg fg-2">
                    <div class="f-section">Account Information</div>
                    <div class="f-field">
                        <label>Username <span class="req">*</span></label>
                        <input type="text" id="up_user_name" placeholder="Username">
                    </div>
                    <div class="f-field">
                        <label>Full Name <span class="req">*</span></label>
                        <input type="text" id="up_name" placeholder="Full Name">
                    </div>
                    <div class="f-field">
                        <label>New Password</label>
                        <input type="password" id="up_password" placeholder="Leave blank to keep current">
                        <span class="f-hint">Leave blank to keep the existing password unchanged</span>
                    </div>
                    <div class="f-section">Role &amp; Branch Access</div>
                    <div class="f-field">
                        <label>Role <span class="req">*</span></label>
                        <select id="up_role">
                            <option value="">— Select Role —</option>
                            @foreach($Userrole as $role)
                            <option value="{{ $role->role_name }}">{{ $role->role_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="f-field">
                        <label>Branch <span class="req">*</span></label>
                        <select id="up_Branch">
                            <option value="">— Select Branch —</option>
                            @foreach($Branch as $b)
                            <option value="{{ $b->name }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <select hidden id="up_BC">
                        @foreach($Branch as $b)
                        <option value="{{ $b->bccode }}">{{ $b->bccode }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-foot">
            <button class="btn-outline" data-close="editUserBg">Cancel</button>
            <button class="btn-blue update_user">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Save Changes
            </button>
        </div>
    </div>
</div>

{{-- ════ ADD ROLE MODAL ════ --}}
<div class="modal-bg" id="addRoleBg">
    <div class="modal-box">
        <div class="modal-head" style="background: linear-gradient(135deg, #e65100, #f57c00);">
            <h4><div class="mh-icon">🏷</div> Add New Role</h4>
            <button class="btn-close-x" data-close="addRoleBg">✕</button>
        </div>
        <div class="modal-body">
            <div class="err-box" id="roleErrBox"></div>
            <form id="addRoleForm">
                @csrf
                <div class="fg fg-2">
                    <div class="f-field">
                        <label>Role Code <span class="req">*</span></label>
                        <input type="text" name="role_code" id="role_code" placeholder="e.g. MGR">
                    </div>
                    <div class="f-field">
                        <label>Role Name <span class="req">*</span></label>
                        <input type="text" name="role_name" id="role_name" placeholder="e.g. Manager">
                    </div>
                    <div class="f-field">
                        <label>Branch (BC)</label>
                       <input type="text" name="BC" id="role_BC" value="{{ Auth::user()->Branch }}" placeholder="e.g. MGR" readonly>
                    </div>
                    <div class="f-field">
                        <label>OC (Other Code)</label>
                        <input type="text" name="OC" id="role_OC"  value="{{ Auth::user()->username }}" readonly>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-foot">
            <button class="btn-outline" data-close="addRoleBg">Cancel</button>
            <button class="btn-amber" id="submitAddRole">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                Create Role
            </button>
        </div>
    </div>
</div>

{!! Toastr::message() !!}

<script>
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

$(document).ready(function () {

    /* Modal helpers */
    function openModal(id)  { $('#' + id).addClass('open'); }
    function closeModal(id) { $('#' + id).removeClass('open'); }

    $('#openAddUserModal').on('click', function () { openModal('addUserBg'); });
    $('#openRoleModal').on('click',    function () { openModal('addRoleBg'); });

    $(document).on('click', '[data-close]', function () { closeModal($(this).data('close')); });
    $(document).on('click', '.modal-bg',    function (e) { if (e.target === this) closeModal($(this).attr('id')); });

    function showErr(id, errors) {
        var $b = $('#' + id).addClass('show').html('');
        $.each(errors, function (i, v) { $b.append('<span>⚠ ' + v + '</span>'); });
    }
    function clearErr(id) { $('#' + id).removeClass('show').html(''); }

    /* ── Add User ── */
    $('#submitAddUser').on('click', function () {
        clearErr('addErrBox');
        $.ajax({
            url: "{{ route('add_user_ajax') }}", method: 'POST',
            data: {
                "_token":  "{{ csrf_token() }}",
                user_name: $('#add_user_name').val(),
                name:      $('#add_name').val(),
                email:     $('#add_email').val(),
                password:  $('#add_password').val(),
                role:      $('#add_role').val(),
                Branch:    $('#add_Branch').val(),
                BC:        $('#add_BC').val(),
            },
            success: function (res) {
                if (res.status === 'success') {
                    closeModal('addUserBg');
                    $('#addUserForm')[0].reset();
                    location.reload();
                    toastr.success("User created successfully!", "Success");
                }
            },
            error: function (err) { showErr('addErrBox', err.responseJSON.errors); }
        });
    });

    /* Branch → BC (Add) */
    $('#add_Branch').on('change', function () {
        $.ajax({
            url: "{{ route('show_select_up_user_ajax') }}", method: 'GET',
            data: { "_token": "{{ csrf_token() }}", category: $(this).val() },
            success: function (res) {
                if (res.status === 'success' && res.data.length) $('#add_BC').val(res.data[0].bccode);
            }
        });
    });

    /* ── Open Edit ── */
    $(document).on('click', '.update_user_form', function (e) {
        e.preventDefault();
        clearErr('editErrBox');
        $('#up_id').val($(this).data('id'));
        $('#up_user_name').val($(this).data('username'));
        $('#up_name').val($(this).data('name'));
        $('#up_role').val($(this).data('role'));
        $('#up_Branch').val($(this).data('branch'));
        $('#up_BC').val($(this).data('bc'));
        $('#up_password').val('');
        openModal('editUserBg');
    });

    /* Branch → BC (Edit) */
    $('#up_Branch').on('change', function () {
        $.ajax({
            url: "{{ route('show_select_up_user_ajax') }}", method: 'GET',
            data: { "_token": "{{ csrf_token() }}", category: $(this).val() },
            success: function (res) {
                if (res.status === 'success') {
                    var $s = $('#up_BC').empty();
                    $.each(res.data, function (i, item) { $s.append($('<option>', { value: item.bccode, text: item.bccode })); });
                }
            }
        });
    });

    /* ── Update User ── */
    $(document).on('click', '.update_user', function (e) {
        e.preventDefault();
        clearErr('editErrBox');
        $.ajax({
            url: "{{ route('update_user_ajax') }}", method: 'POST',
            data: {
                "_token":      "{{ csrf_token() }}",
                up_id:         $('#up_id').val(),
                up_name:       $('#up_name').val(),
                up_user_name:  $('#up_user_name').val(),
                up_role:       $('#up_role').val(),
                up_password:   $('#up_password').val(),
                up_Branch:     $('#up_Branch').val(),
                up_BC:         $('#up_BC').val(),
            },
            success: function (res) {
                if (res.status === 'success') {
                    closeModal('editUserBg');
                    location.reload();
                    toastr.success("User updated successfully!", "Success");
                }
            },
            error: function (err) { showErr('editErrBox', err.responseJSON.errors); }
        });
    });

    /* ── Delete User ── */
    $(document).on('click', '.delete_user', function (e) {
        e.preventDefault();
        var uid = $(this).data('id');
        if (confirm('Are you sure you want to delete this user?')) {
            $.ajax({
                url: "{{ route('delete_user_ajax') }}", method: 'POST',
                data: { "_token": "{{ csrf_token() }}", user_id: uid },
                success: function (res) {
                    if (res.status === 'success') {
                        location.reload();
                        toastr.success("User deleted!", "Success");
                    }
                }
            });
        }
    });

    /* ── Add Role ── */
    $('#submitAddRole').on('click', function () {
        clearErr('roleErrBox');
        $.ajax({
            url: "{{ route('add_role_ajax') }}", method: 'POST',
            data: {
                "_token":   "{{ csrf_token() }}",
                role_code:  $('#role_code').val(),
                role_name:  $('#role_name').val(),
                BC:         $('#role_BC').val(),
                OC:         $('#role_OC').val(),
            },
            success: function (res) {
                if (res.status === 'success') {
                    closeModal('addRoleBg');
                    $('#addRoleForm')[0].reset();
                    toastr.success("Role '" + res.role_name + "' created!", "Success");
                    var $opt = $('<option>', { value: res.role_name, text: res.role_name });
                    $('#add_role, #up_role').append($opt.clone());
                }
            },
            error: function (err) { showErr('roleErrBox', err.responseJSON.errors); }
        });
    });

    /* ── Search ── */
    $('#search').on('keyup', function () {
        $.ajax({
            url: "{{ route('search_user_ajax') }}", method: 'GET',
            data: { search_string: $(this).val() },
            success: function (res) {
                if (res.status === 'not_found') {
                    $('.table-data').html('<p style="text-align:center;padding:40px;color:#9aa5be">No users match your search.</p>');
                } else {
                    $('.table-data').html(res);
                }
            }
        });
    });

    /* ── Pagination ── */
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        $.ajax({ url: "/customer_pagination?page=" + page, success: function (res) { $('.table-data').html(res); } });
    });

});
</script>

<script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>
<script src="assets/js/script.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/js/toastr.min.js"></script>

    <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="assets/plugins/apexchart/chart-data.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
</body>
@endsection
</html>