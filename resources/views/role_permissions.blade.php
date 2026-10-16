@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')
          <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
                <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
                <title>Role Permissions</title>
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">
            </head>

            <style>
                .rp-tabbar{
                    display:flex; gap:4px; border-bottom:1px solid var(--tr-border);
                    margin:-24px -24px 20px; padding:0 24px; overflow-x:auto;
                }
                .rp-tab{
                    display:flex; align-items:center; gap:8px; border:none; background:transparent;
                    padding:16px 6px; font-size:14px; font-weight:600; color:var(--tr-text-secondary);
                    border-bottom:2.5px solid transparent; white-space:nowrap; cursor:pointer;
                    transition:color .15s ease, border-color .15s ease;
                    margin-right:22px;
                }
                .rp-tab:hover{ color:var(--tr-navy); }
                .rp-tab.active{ color:var(--tr-navy); border-color:var(--tr-success); }
                .rp-tab .rp-tab-count{
                    background:var(--tr-bg); color:var(--tr-text-secondary); border-radius:999px;
                    font-size:11.5px; font-weight:700; padding:2px 8px;
                }
                .rp-tab.active .rp-tab-count{ background:#EAFBEF; color:#128A3E; }

                .rp-panel-head{ display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:18px; }

                .rp-all-card{
                    display:flex; align-items:center; gap:14px; background:var(--tr-bg); border:1px solid var(--tr-border);
                    border-radius:12px; padding:14px 16px; margin-bottom:18px;
                }
                .rp-all-card b{ display:block; font-size:14px; color:var(--tr-navy); }
                .rp-all-card span{ font-size:12.5px; color:var(--tr-text-secondary); }

                .rp-switch{ position:relative; width:44px; height:24px; flex:0 0 auto; }
                .rp-switch input{ opacity:0; width:0; height:0; }
                .rp-switch .slider{
                    position:absolute; inset:0; background:#CBD5E1; border-radius:999px; cursor:pointer; transition:.15s;
                }
                .rp-switch .slider::before{
                    content:""; position:absolute; width:18px; height:18px; left:3px; top:3px; background:#fff;
                    border-radius:50%; transition:.15s;
                }
                .rp-switch input:checked + .slider{ background:var(--tr-success); }
                .rp-switch input:checked + .slider::before{ transform:translateX(20px); }

                .rp-grid{ display:grid; grid-template-columns:repeat(auto-fill, minmax(220px, 1fr)); gap:12px; }
                .rp-card{
                    display:block; border:1px solid var(--tr-border); border-radius:12px; padding:13px 15px;
                    cursor:pointer; transition:border-color .15s ease, background .15s ease;
                }
                .rp-card:hover{ border-color:var(--tr-blue); }
                .rp-card.checked{ border-color:var(--tr-success); background:#EAFBEF; }
                .rp-card-top{ display:flex; align-items:flex-start; gap:10px; }
                .rp-card-label{ font-weight:600; font-size:13.5px; color:var(--tr-navy); }

                .rp-card-actions{
                    display:flex; flex-wrap:wrap; gap:6px 10px; margin-top:10px; padding-top:10px;
                    border-top:1px solid var(--tr-border);
                }
                .rp-action-pill{
                    display:flex; align-items:center; gap:5px; font-size:12px; font-weight:600;
                    color:var(--tr-text-secondary); cursor:pointer;
                }
                .rp-action-pill input{ accent-color:var(--tr-success); cursor:pointer; }
                .rp-action-pill input:disabled{ cursor:not-allowed; }
                .rp-card.checked .rp-action-pill{ color:var(--tr-navy); }
                .rp-card:not(.checked) .rp-card-actions{ opacity:.5; }

                .rp-pane{ display:none; }
                .rp-pane.active{ display:block; }

                .rp-reports-row{
                    display:flex; flex-wrap:wrap; gap:6px 10px; margin-top:10px; padding-top:10px;
                    border-top:1px solid var(--tr-border);
                }
                .rp-reports-expand-btn{
                    display:flex; align-items:center; gap:6px; border:none; background:transparent;
                    color:var(--tr-blue); font-size:12px; font-weight:600; cursor:pointer; padding:0;
                }
                .rp-reports-expand-btn .rp-chevron{ transition:transform .15s ease; }
                .rp-reports-expand-btn.open .rp-chevron{ transform:rotate(90deg); }
                .rp-reports-expand-btn:disabled{ opacity:.5; cursor:not-allowed; pointer-events:none; }

                /* Report Selection popup — teleported to <body> at runtime
                   (see script below) so no ancestor wrapper's stacking/
                   overflow context can trap its position:fixed layout. */
                .rp-reports-modal-bg{
                    display:none; position:fixed; inset:0; z-index:99999;
                    background:rgba(15,23,42,.4); backdrop-filter:blur(6px);
                    align-items:center; justify-content:center; padding:24px;
                }
                .rp-reports-modal-bg.open{ display:flex; }
                .rp-reports-modal-box{
                    background:#fff; border-radius:18px;
                    box-shadow:0 24px 64px rgba(15,23,42,.28), 0 2px 10px rgba(15,23,42,.1);
                    width:100%; max-width:680px; height:min(740px, 85vh);
                    display:flex; flex-direction:column; overflow:hidden;
                    animation: rp-modal-in .22s cubic-bezier(.16,1,.3,1);
                }
                @keyframes rp-modal-in {
                    from{ opacity:0; transform:translateY(18px) scale(.97); }
                    to{ opacity:1; transform:translateY(0) scale(1); }
                }
                .rp-reports-modal-head{
                    flex-shrink:0;
                    display:flex; align-items:center; justify-content:space-between; gap:10px;
                    padding:20px 24px; border-radius:18px 18px 0 0;
                    background:#fff; border-bottom:1px solid var(--tr-border);
                }
                .rp-reports-modal-head-text{ display:flex; align-items:center; gap:14px; }
                .rp-reports-modal-icon{
                    width:46px; height:46px; border-radius:12px; flex-shrink:0;
                    background:var(--tr-blue-light); color:var(--tr-blue);
                    display:flex; align-items:center; justify-content:center;
                }
                .rp-reports-modal-body{ padding:18px 24px; overflow-y:auto; flex:1 1 auto; min-height:0; background:#F8FAFC; }
                .rp-reports-modal-foot{
                    flex-shrink:0;
                    padding:16px 24px; border-top:1px solid var(--tr-border);
                    display:flex; justify-content:flex-end; gap:10px; background:#fff; border-radius:0 0 18px 18px;
                }
                .rp-reports-close-btn{
                    width:34px; height:34px; border-radius:9px; border:none; flex-shrink:0;
                    background:var(--tr-bg); color:var(--tr-text-secondary); cursor:pointer; font-size:1.05rem;
                    display:flex; align-items:center; justify-content:center; transition:background .2s, color .2s;
                }
                .rp-reports-close-btn:hover{ background:#E8ECF3; color:var(--tr-navy); }
                .rp-reports-panel-title{ font-size:17px; font-weight:700; color:var(--tr-navy); }
                .rp-reports-panel-sub{ font-size:12.5px; color:var(--tr-text-secondary); margin-top:2px; }
                .rp-reports-panel-actions{
                    display:flex; justify-content:flex-end; gap:8px; margin-bottom:14px;
                }
                .rp-reports-link-btn{
                    border:1px solid var(--tr-border); background:#fff; color:var(--tr-navy);
                    font-size:12px; font-weight:600; cursor:pointer; padding:6px 13px; border-radius:8px;
                    transition:border-color .15s ease, color .15s ease, background .15s ease;
                }
                .rp-reports-link-btn:hover{ border-color:var(--tr-blue); color:var(--tr-blue); background:#F2F7FF; }
                .rp-report-groups{ display:flex; flex-direction:column; gap:12px; }
                .rp-report-group{
                    border:1px solid var(--tr-border); border-radius:12px; overflow:hidden; background:#fff;
                    box-shadow:0 1px 2px rgba(15,23,42,.04);
                }
                .rp-report-group-head{
                    display:flex; align-items:center; gap:10px;
                    padding:12px 14px; background:#fff; cursor:pointer; user-select:none;
                }
                .rp-report-group-chevron{
                    display:flex; align-items:center; justify-content:center; color:var(--tr-text-secondary);
                    transition:transform .18s ease; flex-shrink:0;
                }
                .rp-report-group.collapsed .rp-report-group-chevron{ transform:rotate(-90deg); }
                .rp-report-group-title{
                    font-size:12.5px; font-weight:700; color:var(--tr-navy); letter-spacing:.2px; flex:1;
                }
                .rp-report-group-badge{
                    font-size:10.5px; font-weight:700; color:var(--tr-text-secondary);
                    background:var(--tr-bg); border-radius:999px; padding:2px 9px; flex-shrink:0;
                }
                .rp-report-group-body{ border-top:1px solid var(--tr-border); }
                .rp-report-group.collapsed .rp-report-group-body{ display:none; }
                .rp-report-row{
                    display:flex; align-items:center; justify-content:space-between; gap:10px;
                    padding:10px 14px; border-top:1px solid #F0F2F6; font-size:13px; color:var(--tr-navy);
                    transition:background .12s ease;
                }
                .rp-report-row:first-child{ border-top:none; }
                .rp-report-row:hover{ background:#FAFBFF; }
                .rp-report-switch{ position:relative; width:42px; height:24px; flex:0 0 auto; cursor:pointer; }
                .rp-report-switch input{ opacity:0; width:0; height:0; position:absolute; }
                .rp-report-switch .slider{
                    position:absolute; inset:0; background:#DDE2EA; border-radius:999px; cursor:pointer;
                    transition:background .2s ease, box-shadow .2s ease;
                    box-shadow:inset 0 1px 3px rgba(15,23,42,.1);
                }
                .rp-report-switch .slider::before{
                    content:""; position:absolute; width:18px; height:18px; left:3px; top:3px; background:#fff;
                    border-radius:50%; transition:transform .2s cubic-bezier(.34,1.56,.64,1);
                    box-shadow:0 1px 2px rgba(15,23,42,.2), 0 1px 6px rgba(15,23,42,.12);
                }
                .rp-report-switch:hover .slider{ background:#CCD3DF; }
                .rp-report-switch input:checked + .slider{
                    background:var(--tr-success);
                    box-shadow:inset 0 1px 2px rgba(15,23,42,.08), 0 0 0 3px rgba(22,163,74,.12);
                }
                .rp-report-switch:hover input:checked + .slider{ background:#139438; }
                .rp-report-switch input:checked + .slider::before{ transform:translateX(18px); }
                .rp-report-switch input:focus-visible + .slider{ outline:2px solid var(--tr-blue); outline-offset:2px; }
                .rp-report-switch.rp-report-switch-sm{ width:34px; height:20px; }
                .rp-report-switch.rp-report-switch-sm .slider::before{ width:14px; height:14px; }
                .rp-report-switch.rp-report-switch-sm input:checked + .slider::before{ transform:translateX(14px); }

                @media (prefers-reduced-motion: no-preference) {
                    .rp-pane.active{ animation: rp-pane-in .25s ease both; }
                    @keyframes rp-pane-in { from{ opacity:0; transform:translateY(6px);} to{ opacity:1; transform:translateY(0);} }
                }
            </style>

            <body>

        <div class="main-wrapper">
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <div class="page-header ph-flex">
                        <div class="ph-left">
                            <div class="ph-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </div>
                            <div>
                                <h3 class="page-title">Role Permissions</h3>
                                <p class="page-subtitle">Pick a role's tab and control which modules it can access.</p>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid px-0">
                        <div class="card">
                            <div class="card-body">

                                <div class="rp-tabbar" id="rp-tabbar">
                                    @foreach($roles as $role)
                                    <button type="button" class="rp-tab {{ $role === $initialRole ? 'active' : '' }}" data-role-tab="{{ $role }}">
                                        {{ $role }}
                                        <span class="rp-tab-count">{{ $enabledByRole[$role]->count() }}</span>
                                    </button>
                                    @endforeach
                                </div>

                                @foreach($roles as $role)
                                <div class="rp-pane {{ $role === $initialRole ? 'active' : '' }}" data-role-pane="{{ $role }}">

                                    <div class="rp-panel-head">
                                        <p class="page-subtitle" style="margin:0;">Modules {{ $role }} can see and use</p>
                                        <button type="button" class="btn btn-primary rp-save-btn" data-role="{{ $role }}">
                                            <i class="fas fa-check"></i> Save changes
                                        </button>
                                    </div>

                                    <div class="rp-all-card">
                                        <label class="rp-switch">
                                            <input type="checkbox" class="rp-toggle-all">
                                            <span class="slider"></span>
                                        </label>
                                        <div>
                                            <b>All / Full Access</b>
                                            <span>Turns every module below on or off at once for {{ $role }}.</span>
                                        </div>
                                    </div>

                                    <div class="rp-grid">
                                        @foreach($modules as $key => $label)
                                        @php
                                            $isChecked = $enabledByRole[$role]->contains($key);
                                            $actionsForModule = $moduleActions[$key] ?? [];
                                            $enabledActionsForModule = ($enabledActionsByRole[$role][$key] ?? collect());
                                        @endphp
                                        <div class="rp-card rp-module-card {{ $isChecked ? 'checked' : '' }}" data-module="{{ $key }}">
                                            <label class="rp-card-top" style="cursor:pointer;">
                                                <input type="checkbox" value="{{ $key }}" class="rp-module-checkbox" {{ $isChecked ? 'checked' : '' }}>
                                                <div class="rp-card-label">{{ $label }}</div>
                                            </label>
                                            @if(count($actionsForModule))
                                            <div class="rp-card-actions">
                                                @foreach($actionsForModule as $action)
                                                @php $actionChecked = $enabledActionsForModule->contains($action); @endphp
                                                <label class="rp-action-pill">
                                                    <input type="checkbox" value="{{ $action }}"
                                                        class="rp-action-checkbox" data-module="{{ $key }}"
                                                        {{ $actionChecked ? 'checked' : '' }}
                                                        {{ $isChecked ? '' : 'disabled' }}>
                                                    {{ ucfirst($action) }}
                                                </label>
                                                @endforeach
                                            </div>
                                            @endif
                                            @if($key === 'reports')
                                            @php
                                                $reportTotal = collect($reportGroups)->flatMap(fn($r) => array_keys($r))->count();
                                                $reportEnabledCount = $enabledReportsByRole[$role]->count();
                                            @endphp
                                            <div class="rp-reports-row">
                                                <button type="button" class="rp-reports-expand-btn" data-reports-toggle="{{ $role }}" {{ $isChecked ? '' : 'disabled' }}>
                                                    <svg class="rp-chevron" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                                                    Configure Reports
                                                    <span class="rp-tab-count">{{ $reportEnabledCount }}/{{ $reportTotal }}</span>
                                                </button>
                                            </div>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>

                                    <div class="rp-reports-modal-bg" id="rp-reports-panel-{{ $role }}" data-role="{{ $role }}" data-saved-keys="{{ $enabledReportsByRole[$role]->implode(',') }}">
                                        <div class="rp-reports-modal-box">
                                            <div class="rp-reports-modal-head">
                                                <div class="rp-reports-modal-head-text">
                                                    <div class="rp-reports-modal-icon">
                                                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                                    </div>
                                                    <div>
                                                        <div class="rp-reports-panel-title">Report Access — {{ $role }}</div>
                                                        <div class="rp-reports-panel-sub">Pick exactly which reports this role can open.</div>
                                                    </div>
                                                </div>
                                                <button type="button" class="rp-reports-close-btn" data-close-reports="{{ $role }}">✕</button>
                                            </div>
                                            <div class="rp-reports-modal-body">
                                                <div class="rp-reports-panel-actions">
                                                    <button type="button" class="rp-reports-link-btn rp-reports-select-all" data-role="{{ $role }}">Select All</button>
                                                    <button type="button" class="rp-reports-link-btn rp-reports-select-none" data-role="{{ $role }}">Select None</button>
                                                </div>
                                                <div class="rp-report-groups">
                                                    @foreach($reportGroups as $groupName => $reports)
                                                    @php
                                                        $groupKeys = array_keys($reports);
                                                        $groupEnabledCount = collect($groupKeys)->filter(fn($k) => $enabledReportsByRole[$role]->contains($k))->count();
                                                        $groupAllOn = $groupEnabledCount === count($groupKeys);
                                                    @endphp
                                                    <div class="rp-report-group">
                                                        <div class="rp-report-group-head" data-group-collapse-toggle>
                                                            <span class="rp-report-group-chevron">
                                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                                                            </span>
                                                            <span class="rp-report-group-title">{{ $groupName }}</span>
                                                            <span class="rp-report-group-badge">{{ $groupEnabledCount }}/{{ count($groupKeys) }}</span>
                                                            <label class="rp-report-switch rp-report-switch-sm" title="Toggle all in {{ $groupName }}" onclick="event.stopPropagation();">
                                                                <input type="checkbox" class="rp-report-group-toggle" {{ $groupAllOn ? 'checked' : '' }}>
                                                                <span class="slider"></span>
                                                            </label>
                                                        </div>
                                                        <div class="rp-report-group-body">
                                                            @foreach($reports as $reportKey => $reportLabel)
                                                            @php $reportChecked = $enabledReportsByRole[$role]->contains($reportKey); @endphp
                                                            <div class="rp-report-row">
                                                                <span>{{ $reportLabel }}</span>
                                                                <label class="rp-report-switch">
                                                                    <input type="checkbox" class="rp-report-checkbox" value="{{ $reportKey }}" {{ $reportChecked ? 'checked' : '' }}>
                                                                    <span class="slider"></span>
                                                                </label>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="rp-reports-modal-foot">
                                                <button type="button" class="btn btn-outline-secondary" data-close-reports="{{ $role }}">Cancel</button>
                                                <button type="button" class="btn btn-primary rp-reports-save-btn" data-role="{{ $role }}">
                                                    <i class="fas fa-check"></i> Save Report Access
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
                @include('layouts.footer')
            </div>
         </div>

                    <script>
                    $(document).ready(function () {
                        $.ajaxSetup({
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                        });

                        // Move the report-selection popups to be direct children of <body>.
                        // They start out deeply nested inside the role tabs/cards; if any
                        // ancestor there ever gets a transform/filter (common in dashboard
                        // themes for sidebar/page transitions), it would silently turn their
                        // position:fixed into something scoped to that ancestor instead of
                        // the real viewport, causing a cut-off header and layout cut-off.
                        // Re-parenting to <body> sidesteps that class of bug entirely.
                        $('.rp-reports-modal-bg').appendTo('body');

                        function syncAllToggle($pane) {
                            var $boxes = $pane.find('.rp-module-checkbox');
                            var checked = $boxes.filter(':checked').length;
                            $pane.find('.rp-toggle-all').prop('checked', $boxes.length > 0 && checked === $boxes.length);
                        }
                        $('.rp-pane').each(function () { syncAllToggle($(this)); });

                        $('#rp-tabbar').on('click', '.rp-tab', function () {
                            var role = $(this).data('role-tab');
                            $('.rp-tab').removeClass('active');
                            $(this).addClass('active');
                            $('.rp-pane').removeClass('active');
                            $('.rp-pane[data-role-pane="' + role + '"]').addClass('active');
                        });

                        $(document).on('change', '.rp-module-checkbox', function () {
                            var $pane = $(this).closest('.rp-pane');
                            var $card = $(this).closest('.rp-module-card');
                            $card.toggleClass('checked', this.checked);
                            $card.find('.rp-action-checkbox').prop('disabled', !this.checked);
                            $card.find('.rp-reports-expand-btn').prop('disabled', !this.checked);
                            if (!this.checked) {
                                var role = $card.data('module') === 'reports' ? $pane.data('role-pane') : null;
                                if (role) {
                                    $card.find('.rp-reports-expand-btn').removeClass('open');
                                    $('#rp-reports-panel-' + role).removeClass('open');
                                }
                            }
                            syncAllToggle($pane);
                        });

                        // Resets a report-selection popup's toggles (and its badges) back
                        // to the last actually-saved state, discarding any unsaved
                        // in-progress clicks. Run on open (so a prior session's unsaved
                        // draft never leaks in) and on close-without-saving.
                        function restoreReportsPanel(role) {
                            var $panel = $('#rp-reports-panel-' + role);
                            var saved = ($panel.data('saved-keys') || '').toString().split(',').filter(Boolean);
                            $panel.find('.rp-report-checkbox').each(function () {
                                $(this).prop('checked', saved.indexOf(this.value) !== -1);
                            });
                            $panel.find('.rp-report-group').each(function () { syncGroupBadge($(this)); });
                            syncReportsBadge(role);
                        }

                        /* ── Reports: open/close the report-selection popup ── */
                        $(document).on('click', '.rp-reports-expand-btn', function () {
                            if (this.disabled) return;
                            var role = $(this).data('reports-toggle');
                            restoreReportsPanel(role);
                            $('#rp-reports-panel-' + role).addClass('open');
                            $('body').css('overflow', 'hidden');
                        });

                        $(document).on('click', '[data-close-reports]', function () {
                            var role = $(this).data('close-reports');
                            restoreReportsPanel(role);
                            $('#rp-reports-panel-' + role).removeClass('open');
                            $('body').css('overflow', '');
                        });

                        $(document).on('click', '.rp-reports-modal-bg', function (e) {
                            if (e.target === this) {
                                restoreReportsPanel($(this).data('role'));
                                $(this).removeClass('open');
                                $('body').css('overflow', '');
                            }
                        });

                        /* ── Reports: expand/collapse a group ── */
                        $(document).on('click', '[data-group-collapse-toggle]', function () {
                            $(this).closest('.rp-report-group').toggleClass('collapsed');
                        });

                        function syncGroupBadge($group) {
                            var $boxes = $group.find('.rp-report-checkbox');
                            var checked = $boxes.filter(':checked').length;
                            $group.find('.rp-report-group-badge').text(checked + '/' + $boxes.length);
                            $group.find('.rp-report-group-toggle').prop('checked', $boxes.length > 0 && checked === $boxes.length);
                        }

                        // Updates the "Configure Reports x/y" badge on the module card.
                        // Looked up by [data-reports-toggle=role] rather than DOM
                        // proximity, since the popup itself lives under <body> (see the
                        // reparenting note above) and is no longer a descendant of the
                        // role pane the button sits in.
                        function syncReportsBadge(role) {
                            var $panel = $('#rp-reports-panel-' + role);
                            var $boxes = $panel.find('.rp-report-checkbox');
                            var checked = $boxes.filter(':checked').length;
                            $('[data-reports-toggle="' + role + '"] .rp-tab-count').text(checked + '/' + $boxes.length);
                        }

                        /* ── Reports: group "toggle all" switch ── */
                        $(document).on('change', '.rp-report-group-toggle', function () {
                            var $group = $(this).closest('.rp-report-group');
                            $group.find('.rp-report-checkbox').prop('checked', this.checked);
                            syncGroupBadge($group);
                            syncReportsBadge($(this).closest('.rp-reports-modal-bg').data('role'));
                        });

                        $(document).on('change', '.rp-report-checkbox', function () {
                            syncGroupBadge($(this).closest('.rp-report-group'));
                            syncReportsBadge($(this).closest('.rp-reports-modal-bg').data('role'));
                        });

                        /* ── Reports: select all / select none ── */
                        $(document).on('click', '.rp-reports-select-all', function () {
                            var role = $(this).data('role');
                            var $panel = $('#rp-reports-panel-' + role);
                            $panel.find('.rp-report-checkbox, .rp-report-group-toggle').prop('checked', true);
                            $panel.find('.rp-report-group').each(function () { syncGroupBadge($(this)); });
                            syncReportsBadge(role);
                        });
                        $(document).on('click', '.rp-reports-select-none', function () {
                            var role = $(this).data('role');
                            var $panel = $('#rp-reports-panel-' + role);
                            $panel.find('.rp-report-checkbox, .rp-report-group-toggle').prop('checked', false);
                            $panel.find('.rp-report-group').each(function () { syncGroupBadge($(this)); });
                            syncReportsBadge(role);
                        });

                        /* ── Reports: save ── */
                        $(document).on('click', '.rp-reports-save-btn', function () {
                            var $btn = $(this);
                            var role = $btn.data('role');
                            var $panel = $('#rp-reports-panel-' + role);
                            var reports = $panel.find('.rp-report-checkbox:checked').map(function () {
                                return this.value;
                            }).get();
                            var previousSavedKeys = $panel.attr('data-saved-keys');

                            // Update the badge and close the popup immediately — we already
                            // know the new count from what's checked right now, no need to
                            // wait on the network round-trip before the UI reflects it.
                            $panel.attr('data-saved-keys', reports.join(','));
                            $panel.data('saved-keys', reports.join(','));
                            syncReportsBadge(role);
                            $panel.removeClass('open');
                            $('body').css('overflow', '');

                            $btn.prop('disabled', true);
                            $.ajax({
                                type: 'POST',
                                url: "{{ route('role_permissions.reports.save') }}",
                                data: { role_name: role, reports: reports },
                                success: function () {
                                    $('<div class="save-success-toast">' +
                                        '<div class="toast-check"><i class="fas fa-check"></i></div>' +
                                        '<div>' +
                                            '<div class="toast-text">Saved!</div>' +
                                            '<div class="toast-subtext">Report access updated for ' + role + '.</div>' +
                                        '</div>' +
                                    '</div>').appendTo('body');

                                    $btn.prop('disabled', false);

                                    setTimeout(function () {
                                        var t = document.querySelector('.save-success-toast');
                                        if (t) t.remove();
                                    }, 4000);
                                },
                                error: function () {
                                    $btn.prop('disabled', false);
                                    // The server never actually persisted this — roll the
                                    // optimistic badge/snapshot update back to what was truly
                                    // last saved, instead of leaving a false "saved" state.
                                    $panel.attr('data-saved-keys', previousSavedKeys);
                                    $panel.data('saved-keys', previousSavedKeys);
                                    restoreReportsPanel(role);
                                    alert('Something went wrong while saving — reopen "Configure Reports" to try again.');
                                }
                            });
                        });

                        $(document).on('change', '.rp-toggle-all', function () {
                            var $pane = $(this).closest('.rp-pane');
                            var on = this.checked;
                            $pane.find('.rp-module-checkbox').prop('checked', on).trigger('change');
                        });

                        $(document).on('click', '.rp-save-btn', function () {
                            var $btn = $(this);
                            var role = $btn.data('role');
                            var $pane = $btn.closest('.rp-pane');
                            var modules = $pane.find('.rp-module-checkbox:checked').map(function () {
                                return this.value;
                            }).get();

                            var actions = {};
                            $pane.find('.rp-action-checkbox:checked').each(function () {
                                var moduleKey = $(this).data('module');
                                if (!actions[moduleKey]) actions[moduleKey] = [];
                                actions[moduleKey].push(this.value);
                            });

                            $btn.prop('disabled', true);
                            $.ajax({
                                type: 'POST',
                                url: "{{ route('role_permissions.save') }}",
                                data: { role_name: role, modules: modules, actions: actions },
                                success: function () {
                                    $('.rp-tab[data-role-tab="' + role + '"] .rp-tab-count').text(modules.length);

                                    $('<div class="save-success-toast">' +
                                        '<div class="toast-check"><i class="fas fa-check"></i></div>' +
                                        '<div>' +
                                            '<div class="toast-text">Saved!</div>' +
                                            '<div class="toast-subtext">Permissions updated for ' + role + '.</div>' +
                                        '</div>' +
                                    '</div>').appendTo('body');

                                    $btn.prop('disabled', false);

                                    setTimeout(function () {
                                        var t = document.querySelector('.save-success-toast');
                                        if (t) t.remove();
                                    }, 4000);
                                },
                                error: function () {
                                    $btn.prop('disabled', false);
                                    alert('Something went wrong while saving.');
                                }
                            });
                        });
                    });
                    </script>

<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/js/script.js"></script>

</body>
@endsection

</html>
