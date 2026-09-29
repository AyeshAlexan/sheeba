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
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <title>Chart Of Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
</head>
<style>
    .coa-wrap{ padding: 24px; background: #F8FAFC; }
    .coa-header{ display:flex; align-items:flex-start; gap:16px; margin-bottom:20px; }
    .coa-header-icon{
        width:48px; height:48px; border-radius:12px; background:#DCFCE7; color:#15803D;
        display:flex; align-items:center; justify-content:center; font-size:20px; flex:0 0 auto;
    }
    .coa-header-text{ flex:1; }
    .coa-header-text h3{ margin:0; font-weight:700; color:#0F172A; font-size:22px; }
    .coa-header-text p{ margin:4px 0 0; color:#64748B; font-size:13.5px; }
    .coa-add-btn{
        background:#16A34A; color:#fff; border:none; border-radius:10px; padding:11px 18px;
        font-weight:600; font-size:13.5px; white-space:nowrap; align-self:flex-start;
    }
    .coa-add-btn:hover{ background:#15803D; color:#fff; }

    .coa-info{
        display:flex; align-items:center; gap:10px; background:#EFF6FF; border:1px solid #BFDBFE;
        color:#1D4ED8; border-radius:10px; padding:13px 16px; font-size:13.5px; margin-bottom:20px;
    }
    .coa-info i{ font-size:15px; flex:0 0 auto; }

    .coa-card{
        background:#fff; border:1px solid #E5E7EB; border-radius:14px; margin-bottom:16px;
        overflow:hidden; box-shadow:0 1px 2px rgba(15,23,42,.04);
    }
    .coa-card-head{
        display:flex; align-items:center; gap:10px; padding:16px 18px; cursor:pointer;
        border-bottom:1px solid #F1F5F9;
    }
    .coa-type-icon{
        width:30px; height:30px; border-radius:8px; display:flex; align-items:center; justify-content:center;
        font-size:13px; flex:0 0 auto;
    }
    .coa-type-badge{ font-weight:700; font-size:14px; padding:5px 14px; border-radius:999px; flex:0 0 auto; }
    .coa-spacer{ flex:1; }
    .coa-count{ color:#94A3B8; font-size:13px; margin-right:12px; }
    .coa-chevron{ color:#94A3B8; transition:transform .15s; }
    .coa-card-head.collapsed .coa-chevron{ transform:rotate(180deg); }

    .coa-colhead{
        display:flex; align-items:center; gap:14px; padding:10px 18px;
        background:#F8FAFC; color:#94A3B8; font-size:11.5px; font-weight:700;
        text-transform:uppercase; letter-spacing:.04em;
    }
    .coa-colhead span:nth-child(1){ width:130px; flex:0 0 auto; }
    .coa-colhead span:nth-child(2){ flex:1; }
    .coa-colhead span:nth-child(3){ flex:0 0 auto; }

    .coa-group-row{
        display:flex; align-items:center; gap:8px; padding:10px 18px;
        color:#64748B; font-size:13px; background:#FAFBFC; border-top:1px solid #F1F5F9;
    }
    .coa-group-dot{ width:6px; height:6px; border-radius:50%; background:#CBD5E1; flex:0 0 auto; }

    .coa-row{
        display:flex; align-items:center; gap:14px; padding:12px 18px 12px 34px;
        border-top:1px solid #F1F5F9;
    }
    .coa-row:hover{ background:#F8FAFC; }
    .coa-code{
        font-family: ui-monospace, "SF Mono", Consolas, monospace; font-size:12.5px; font-weight:600;
        background:#FEE2E2; color:#B91C1C; border-radius:999px; padding:5px 12px;
        width:130px; flex:0 0 auto; text-align:center; letter-spacing:.03em;
    }
    .coa-name{ flex:1; color:#1E293B; font-size:14px; }
    .coa-dupe{
        display:inline-flex; align-items:center; gap:6px; background:#FCA5A5; color:#7F1D1D;
        font-size:11.5px; font-weight:700; border-radius:999px; padding:5px 12px; flex:0 0 auto;
        white-space:nowrap;
    }
    .coa-icon-btn{
        width:32px; height:32px; border-radius:9px; border:none; display:inline-flex;
        align-items:center; justify-content:center; flex:0 0 auto; font-size:13px;
    }
    .coa-edit{ background:#DBEAFE; color:#1D4ED8; }
    .coa-edit:hover{ background:#BFDBFE; }
    .coa-delete{ background:#FEE2E2; color:#DC2626; }
    .coa-delete:hover{ background:#FECACA; }

    .coa-empty{
        background:#fff; border:1px dashed #E5E7EB; border-radius:14px; padding:40px; text-align:center;
        color:#94A3B8;
    }
</style>


<body>
    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="coa-wrap">

                    <div class="coa-header">
                        <div class="coa-header-icon"><i class="fas fa-book"></i></div>
                        <div class="coa-header-text">
                            <h3>Chart Of Account</h3>
                            <p>Manage your chart of accounts and organize your financial structure.</p>
                        </div>
                        <button type="button" class="coa-add-btn" onclick="add()">
                            <i class="fas fa-plus"></i> Add Chart Of Account
                        </button>
                    </div>

                    @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                    @endif

                    <div class="coa-info">
                        <i class="fas fa-info-circle"></i>
                        <span>A chart of accounts is a directory of account names and codes — not a report of balances. For live figures, see Trial Balance.</span>
                    </div>

                    @php
                        $typeStyle = [
                            'Assets'      => ['icon' => 'fa-layer-group',     'bg' => '#DCFCE7', 'fg' => '#15803D', 'iconbg' => '#BBF7D0'],
                            'Liabilities' => ['icon' => 'fa-balance-scale',  'bg' => '#FEF3C7', 'fg' => '#B45309', 'iconbg' => '#FDE68A'],
                            'Equity'      => ['icon' => 'fa-chart-pie',       'bg' => '#EDE9FE', 'fg' => '#6D28D9', 'iconbg' => '#DDD6FE'],
                            'Income'      => ['icon' => 'fa-chart-line',  'bg' => '#D1FAE5', 'fg' => '#047857', 'iconbg' => '#A7F3D0'],
                            'Revenue'     => ['icon' => 'fa-chart-line',  'bg' => '#D1FAE5', 'fg' => '#047857', 'iconbg' => '#A7F3D0'],
                            'Expenses'    => ['icon' => 'fa-file-invoice',    'bg' => '#DBEAFE', 'fg' => '#1D4ED8', 'iconbg' => '#BFDBFE'],
                        ];
                        $defaultStyle = ['icon' => 'fa-folder', 'bg' => '#E5E7EB', 'fg' => '#374151', 'iconbg' => '#D1D5DB'];
                    @endphp

                    @forelse($tree as $type => $groups)
                        @php $style = $typeStyle[$type] ?? $defaultStyle; @endphp
                        <div class="coa-card">
                            <div class="coa-card-head" role="button" data-bs-toggle="collapse" data-bs-target="#coa-type-{{ $loop->index }}">
                                <span class="coa-type-icon" style="background: {{ $style['iconbg'] }}; color: {{ $style['fg'] }};">
                                    <i class="fas {{ $style['icon'] }}"></i>
                                </span>
                                <span class="coa-type-badge" style="background: {{ $style['bg'] }}; color: {{ $style['fg'] }};">{{ $type }}</span>
                                <span class="coa-spacer"></span>
                                <span class="coa-count">{{ $groups->flatten(1)->count() }} accounts</span>
                                <i class="fas fa-chevron-up coa-chevron"></i>
                            </div>

                            <div class="collapse show" id="coa-type-{{ $loop->index }}">
                                <div class="coa-colhead">
                                    <span>Account Code</span>
                                    <span>Account Name</span>
                                    <span>Actions</span>
                                </div>

                                @foreach($groups as $group => $rows)
                                <div class="coa-group-row">
                                    <span class="coa-group-dot"></span>
                                    <span>{{ $group }}</span>
                                    <span class="coa-spacer"></span>
                                    <span>{{ $rows->count() }} accounts</span>
                                </div>
                                @foreach($rows as $row)
                                <div class="coa-row">
                                    <span class="coa-code">{{ str_replace('-', ' - ', $row->code) }}</span>
                                    <span class="coa-name">{{ $row->description }}</span>
                                    <span class="coa-spacer"></span>
                                    @if($duplicateCodes->contains($row->code))
                                    <span class="coa-dupe" title="Another account uses this same code">
                                        <i class="fas fa-exclamation-triangle"></i> Duplicate code {{ $row->code }}
                                    </span>
                                    @endif
                                    <button type="button" class="coa-icon-btn coa-edit" onclick="editFunc({{ $row->id }})">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button type="button" class="coa-icon-btn coa-delete" onclick="deleteFunc({{ $row->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                @endforeach
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="coa-empty">No accounts yet — click "Add Chart Of Account" to create one.</div>
                    @endforelse

                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>


                <!-- add MChartofAccount model -->
                    <div class="modal fade" id="Item-modal" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Chart Of Account</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                        <form action="javascript:void(0)" id="ItemForm" name="ItemForm"
                                            class="form-horizontal" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="id" id="id">
                                            <div class="row">
                                              <div class="col-sm-6">
                                                <label for="accountsub" class="col-sm-6 control-label">Account Type</label>
                                                <select class="select form-control" name="accountsub" id="accountsub"
                                                    aria-hidden="true">
                                                    <option value="">Please Select</option>
                                                    @foreach($MainCategoryData as $categoryData)
                                                    <option value="{{ $categoryData->category}}">
                                                        {{ $categoryData->category }}</option>
                                                    @endforeach
                                                </select>
                                                <small class="text-muted">Assets / Liabilities / Equity / Income / Expenses</small>
                                            </div>

                                            <div class="col-sm-6">
                                                <label for="account" class="col-sm-6 control-label">Account Group</label>
                                                <select class="select form-control" name="account" id="account"
                                                    aria-hidden="true">
                                                    <option value="">Please Select</option>
                                                    @foreach($AccountTypeData as $categoryData)
                                                    <option value="{{ $categoryData->description}}">
                                                        {{ $categoryData->description }}</option>
                                                    @endforeach
                                                </select>
                                                <small class="text-muted">Which section of the Account Type this belongs under</small>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col 6">
                                                <div class="form-group">
                                                 <label for="name" class="col-sm-4 control-label">Code
                                                    {{-- <span style="color:#FF0000; font-weight: bold; ">*</span> --}}
                                                </label>
                                                  <div class="col-sm-12">
                                                  <input type="text" class="form-control" id="code"
                                                  {{-- value="{{ $maxCustomer+1}}" --}}
                                                   name="code" placeholder="Code" maxlength="15" required="">
                                                   <span class="text-danger" id="image-input-error"></span>
                                              </div>
                                          </div>
                                         </div>
                                         <div class="col 6">
                                            <div class="form-group">
                                             <label for="name" class="col-sm-4 control-label">Description
                                                {{-- <span style="color:#FF0000; font-weight: bold; ">*</span> --}}
                                            </label>
                                              <div class="col-sm-12">
                                              <input type="text" class="form-control" id="description"
                                              {{-- value="{{ $maxCustomer+1}}" --}}
                                               name="description" placeholder="Description" maxlength="15" required="">
                                               <span class="text-danger" id="image-input-error"></span>
                                          </div>
                                      </div>
                                     </div>
                                   </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                      <div class="card">
                                        <div class="card-body">
                                            <h6>Options
                                                {{-- <span style="color:#FF0000; font-weight: bold; ">*</span> --}}
                                            </h6>
                                            <hr>
                                                <div class="col 6">
                                                    <div class="form-check">
                                                        <label class="form-check-label" for="defaultCheck1">
                                                          Control Account
                                                        </label>
                                                        <input class="form-check-input" type="checkbox" value="1" id="controlaccount" name="controlaccount">
                                                      </div>
                                                </div>
                                                <div class="col 6">
                                                    <div class="form-check">
                                                        <label class="form-check-label" for="defaultCheck1">
                                                            Bank Account
                                                        </label>
                                                        <input class="form-check-input" type="checkbox" value="1" id="bankaccount" name="bankaccount">
                                                      </div>
                                                </div>
                                            </div>
                                      </div>
                                    </div>
                                  </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="hidden" class="form-control" id="OC"
                                                    name="OC" placeholder=""
                                                    value="{{ Auth::user()->username}}" readonly>
                                                </div>

                                                <div class="col-md-6">
                                                   <input type="hidden" class="form-control" id="BC"
                                                   name="BC" placeholder=""
                                                   value="{{ Auth::user()->BC}}" readonly>
                                               </div>
                                           </div>

                                            <div class="col-sm-offset-2 col-sm-10 text-center"><br />
                                                <button type="submit" class="btn btn-info" id="btn-save">Save
                                                    changes</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                                <div class="modal-footer"></div>
                            </div>
                        </div>
                    </div>

                    <!-- end bootstrap model -->
                    <script type="text/javascript">
                        $(document).ready(function () {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                        });

                        function add() {
                            $('#ItemForm').trigger("reset");
                            $('#ItemModal').html("Add Chart of Account");
                            $('#Item-modal').modal('show');
                            $('#id').val('');
                        }

                        function editFunc(id) {
                            $.ajax({
                                type: "POST",
                                url: "{{ url('ChartofAccount_edit') }}",
                                data: {
                                    id: id
                                },
                                dataType: 'json',
                                success: function (res) {
                                    $('#ItemModal').html("Edit Item");
                                    $('#Item-modal').modal('show');
                                    $('#id').val(res.id);
                                    $('#account').val(res.account);
                                    $('#accountsub').val(res.accountsub);
                                    $('#code').val(res.code);
                                    $('#description').val(res.description);
                                    $('#controlaccount').val(res.controlaccount);
                                    $('#bankaccount').val(res.bankaccount);
                                    $('#BC').val(res.BC);
                                    $('#OC').val(res.OC);
                                }
                            });
                        }

                        function deleteFunc(id) {
                            if (confirm("Delete Record?") == true) {
                                var id = id;
                                // ajax
                                $.ajax({
                                    type: "POST",
                                    url: "{{ url('ChartofAccount_delete') }}",
                                    data: {
                                        id: id
                                    },
                                    dataType: 'json',
                                    success: function (res) {
                                        window.location.reload();
                                    }
                                });
                            }
                        }

                        $('#ItemForm').submit(function (e) {
                            e.preventDefault();
                            var formData = new FormData(this);
                            $.ajax({
                                type: 'POST',
                                url: "{{ url('ChartofAccount_store')}}",
                                data: formData,
                                cache: false,
                                contentType: false,
                                processData: false,
                                success: (data) => {
                                    window.location.reload();
                                },
                                error: function (xhr) {
                                    $("#btn-save").html('Submit');
                                    $("#btn-save").attr("disabled", false);
                                    let msg = 'Something went wrong while saving.';
                                    if (xhr.responseJSON) {
                                        if (xhr.responseJSON.errors) {
                                            msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                                        } else if (xhr.responseJSON.message) {
                                            msg = xhr.responseJSON.message;
                                        }
                                    }
                                    alert(msg);
                                }
                            });
                        });
                    </script>

                    <script>
                        data - cfasync = "false"
                        src = "../../../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js" >

                    </script>
                    <script src="assets/js/jquery-3.6.0.min.js"></script>
                    <script src="assets/js/bootstrap.bundle.min.js"></script>
                    <script src="assets/js/feather.min.js"></script>
                    {{-- <script src="assets/plugins/select2/js/select2.min.js"></script> --}}
                    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
                    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
                    <script src="assets/plugins/datatables/datatables.min.js"></script>
                    <script src="assets/js/script.js"></script>

                    <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
                    <script src="assets/plugins/apexchart/chart-data.js"></script>

</body>
@endsection

</html>
