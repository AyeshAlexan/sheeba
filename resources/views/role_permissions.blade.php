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

                .rp-pane{ display:none; }
                .rp-pane.active{ display:block; }

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
                                        @php $isChecked = $enabledByRole[$role]->contains($key); @endphp
                                        <label class="rp-card rp-module-card {{ $isChecked ? 'checked' : '' }}">
                                            <div class="rp-card-top">
                                                <input type="checkbox" value="{{ $key }}" class="rp-module-checkbox" {{ $isChecked ? 'checked' : '' }}>
                                                <div class="rp-card-label">{{ $label }}</div>
                                            </div>
                                        </label>
                                        @endforeach
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
                            $(this).closest('.rp-module-card').toggleClass('checked', this.checked);
                            syncAllToggle($pane);
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

                            $btn.prop('disabled', true);
                            $.ajax({
                                type: 'POST',
                                url: "{{ route('role_permissions.save') }}",
                                data: { role_name: role, modules: modules },
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
