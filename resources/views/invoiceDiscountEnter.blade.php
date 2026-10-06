{{-- @extends('layouts.app') --}}
@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Customer Discount</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        #customer_list {
            border: 1px solid var(--tr-border);
            border-radius: 8px;
            margin-top: 4px;
            box-shadow: 0 8px 22px rgba(11,42,84,.08);
            overflow: hidden;
            max-height: 280px;
            overflow-y: auto;
        }
        #customer_list .list-group-item {
            border: none;
            border-bottom: 1px solid var(--tr-border);
            padding: 10px 14px;
            font-size: 13.5px;
            color: var(--tr-text);
        }
        #customer_list .list-group-item:last-child { border-bottom: none; }
        #customer_list .list-group-item-action:hover { background: var(--tr-blue-light); }
        #customer_list .list-group-item strong { color: var(--tr-blue); margin-right: 4px; }
        #customer_list .list-group-item i { color: var(--tr-blue); margin-right: 8px; width: 14px; }

        .idc-count {
            font-size: 12px;
            color: var(--tr-blue);
            background: var(--tr-blue-light);
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 600;
        }
        .idc-count:empty { display: none; }

        #invoices_table td:nth-child(3),
        #invoices_table td:nth-child(4),
        #invoices_table td:nth-child(5),
        #invoices_table td:nth-child(6) {
            text-align: right;
            font-variant-numeric: tabular-nums;
        }
        #invoices_table td:last-child { text-align: center; }

        .balance-cell.is-clear { color: var(--tr-success); font-weight: 600; }
        .balance-cell.is-due { color: var(--tr-danger); font-weight: 600; }
        .balance-cell i { margin-right: 5px; font-size: 11px; }

        .idc-empty-state {
            text-align: center;
            color: var(--tr-text-muted);
            font-size: 13.5px;
            padding: 40px 16px !important;
        }
        .idc-empty-state i {
            display: block;
            font-size: 22px;
            margin-bottom: 8px;
            color: var(--tr-border);
        }

        #discount_error, #transport_error {
            background: #FEECEC;
            color: var(--tr-danger);
            border-radius: 6px;
            padding: 8px 12px;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="page-header ph-flex">
                    <div class="ph-left">
                        <div class="ph-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 1v22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <div>
                            <h3 class="page-title">Customer Discount</h3>
                            <p class="page-subtitle">Apply discounts and transport charges against a customer's invoices</p>
                        </div>
                    </div>
                </div>

                {{-- Customer Lookup card --}}
                <div class="stock-info-card">
                    <div class="stock-info-grid-3">
                        <div class="si-field">
                            <label>Search Customer <span class="text-danger">*</span></label>
                            <div class="stock-item-search-row">
                                <div class="stock-item-search-wrap">
                                    <i class="fas fa-search"></i>
                                    <input type="text" id="customer_search" class="form-control" autocomplete="off"
                                        placeholder="Code, Name, NIC, or Phone...">
                                    <div id="customer_list" class="list-group position-absolute w-100 shadow" style="z-index: 1000; display: none;"></div>
                                </div>
                                <button type="button" class="stock-item-search-btn"
                                    data-bs-toggle="modal" data-bs-target="#selectCustomerModel" title="Search customer">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="si-field">
                            <label>Selected Customer Code</label>
                            <div class="si-icon-wrap">
                                <i class="fas fa-id-card"></i>
                                <input type="text" id="selected_customer_code" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="si-field">
                            <label>Total Outstanding Balance</label>
                            <div class="si-icon-wrap">
                                <i class="fas fa-scale-balanced"></i>
                                <input type="text" id="customer_outstanding_balance" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Invoices table card --}}
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                            <h5 class="mb-0"><i class="fas fa-file-lines text-primary me-2"></i>Customer Invoices</h5>
                            <span class="idc-count" id="idc-invoice-count"></span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0" id="invoices_table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Invoice No</th>
                                        <th>Date</th>
                                        <th>Gross Amount</th>
                                        <th>Discount</th>
                                        <th>Net Amount</th>
                                        <th>Balance</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="7" class="idc-empty-state">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                            Select a customer to display invoices
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal fade" id="selectCustomerModel" tabindex="-1" role="dialog"
                 aria-labelledby="selectCustomerModelLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title m-2" id="selectCustomerModelLabel">Search Customer</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-center table-hover mt-3" id="DiscountCustomerTable">
                                    <thead>
                                        <tr class="table-secondary">
                                            <th>Code</th>
                                            <th>Name</th>
                                            <th>Address</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($customerDetails as $data)
                                        <tr>
                                            <td>{{ $data->Code }}</td>
                                            <td><div class="item-description-wrapper">{{ $data->First_name }}</div></td>
                                            <td><div class="item-description-wrapper">{{ $data->Address_1 }}</div></td>
                                            <td>
                                                <a href="#" class="btn btn-outline-info btn-sm shadow select-customer"
                                                   data-code="{{ $data->Code }}" data-name="{{ $data->First_name }}"
                                                   data-bs-dismiss="modal">
                                                    Add <i class="fas fa-plus"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div id="DiscountCustomerCustomPager"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script src="assets/js/dt-custom-pager.js"></script>
            <script>
                $(document).ready(function () {
                    var DiscountCustomerDt = $('#DiscountCustomerTable').DataTable({
                        pageLength: 10,
                        lengthChange: false,
                        dom: 'ft',
                    });
                    DTCustomPager.init(DiscountCustomerDt, '#DiscountCustomerCustomPager');
                });
            </script>

            @include('layouts.footer')
        </div>
    </div>

    <!-- Apply Discount Modal -->
    <div class="modal fade" id="discountModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="fa-solid fa-tag me-2"></i>Apply Discount</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="discount_invoice_id">
            <input type="hidden" id="discount_invoice_no">
            <input type="hidden" id="discount_invoice_date">

            <div class="mb-3">
                <label class="form-label">Invoice No</label>
                <input type="text" id="discount_invoice_no_display" class="form-control" readonly>
            </div>
            <div class="mb-3" style="display: none">
                <label class="form-label">Current Balance</label>
                <input type="text" id="discount_current_balance" class="form-control" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Discount Amount</label>
                <input type="number" step="0.01" min="0.01" id="discount_amount_input" class="form-control">
            </div>
            <div id="discount_error" class="small" style="display:none;"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> Cancel</button>
            <button type="button" class="btn btn-primary" id="confirm_discount_btn"><i class="fa-solid fa-check"></i> Confirm</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Transport Payment Modal -->
    <div class="modal fade" id="transportModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="fa-solid fa-truck me-2"></i>Transport Payment</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="transport_invoice_id">
            <input type="hidden" id="transport_invoice_no">
            <input type="hidden" id="transport_invoice_date">

            <div class="mb-3">
                <label class="form-label">Invoice No</label>
                <input type="text" id="transport_invoice_no_display" class="form-control" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Transport Amount</label>
                <input type="number" step="0.01" min="0.01" id="transport_amount_input" class="form-control">
            </div>
            <div id="transport_error" class="small" style="display:none;"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> Cancel</button>
            <button type="button" class="btn btn-primary" id="confirm_transport_btn"><i class="fa-solid fa-check"></i> Confirm</button>
          </div>
        </div>
      </div>
    </div>

{{-- ════ Scripts ════ --}}
<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/js/toastr.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/js/script.js"></script>
<script>
$(document).ready(function () {
    let timer = null;

    // Customer Search Autocomplete
    $('#customer_search').on('keyup', function () {
        clearTimeout(timer);
        let query = $(this).val();

        if (query.length < 2) {
            $('#customer_list').hide();
            return;
        }

        timer = setTimeout(function () {
            $.ajax({
                url: "{{ route('customers.search') }}",
                type: "GET",
                data: { term: query },
                success: function (data) {
                    let dropdown = $('#customer_list');
                    dropdown.empty().show();

                    if (data.length === 0) {
                        dropdown.append('<div class="list-group-item">No customers found</div>');
                        return;
                    }

                    $.each(data, function (key, customer) {
                        let displayName = customer.First_name || customer.Name;
                        dropdown.append(`
                            <a href="#" class="list-group-item list-group-item-action select-customer"
                               data-code="${customer.Code}"
                               data-name="${displayName}">
                                <i class="fa-solid fa-user"></i><strong>${customer.Code}</strong> - ${displayName} (NIC: ${customer.NIC || 'N/A'})
                            </a>
                        `);
                    });
                }
            });
        }, 300);
    });

    // Handle Customer Selection
    $(document).on('click', '.select-customer', function (e) {
        e.preventDefault();
        let code = $(this).data('code');
        let name = $(this).data('name');

        $('#customer_search').val(`${code} - ${name}`);
        $('#selected_customer_code').val(code);
        $('#customer_list').hide();

        fetchInvoices(code);
        fetchCustomerBalance(code);
    });

    // Fetch Invoices Function
    function fetchInvoices(customerCode) {
        $.ajax({
            url: "{{ route('invoices.by-customer') }}",
            type: "GET",
            data: { customer_code: customerCode },
            success: function (invoices) {
                let tbody = $('#invoices_table tbody');
                tbody.empty();
                $('#idc-invoice-count').text(invoices.length ? invoices.length + ' invoice' + (invoices.length === 1 ? '' : 's') : '');

                if (invoices.length === 0) {
                    tbody.append(`
                        <tr>
                            <td colspan="7" class="idc-empty-state">
                                <i class="fa-solid fa-file-circle-xmark"></i>
                                No invoices found for this customer.
                            </td>
                        </tr>
                    `);
                    return;
                }

                $.each(invoices, function (index, invoice) {
                    let balance = parseFloat(invoice.Balance || 0);
                    let balanceClass = balance > 0 ? 'is-due' : 'is-clear';
                    let balanceIcon = balance > 0 ? 'fa-triangle-exclamation' : 'fa-circle-check';

                    tbody.append(`
                        <tr data-id="${invoice.id}">
                            <td>${invoice.Invoice_no}</td>
                            <td>${invoice.Invoice_date}</td>
                            <td>${parseFloat(invoice.Gross_Amount || 0).toFixed(2)}</td>
                            <td class="discount-cell">${parseFloat(invoice.after_customer_discount || 0).toFixed(2)}</td>
                            <td class="net-cell">${parseFloat(invoice.Net_Amount || 0).toFixed(2)}</td>
                            <td class="balance-cell ${balanceClass}"><i class="fa-solid ${balanceIcon}"></i>${balance.toFixed(2)}</td>
                            <td class="text-center">
                             <button class="btn btn-sm btn-outline-info edit-discount-btn"
                                data-id="${invoice.id}"
                                data-invoice-no="${invoice.Invoice_no}"
                                data-invoice-date="${invoice.Invoice_date}"
                                data-balance="${invoice.Balance}">
                                <i class="fa-solid fa-tag"></i> Discount
                            </button>
                             <button class="btn btn-sm btn-outline-success transport-payment-btn"
                                data-id="${invoice.id}"
                                data-invoice-no="${invoice.Invoice_no}"
                                data-invoice-date="${invoice.Invoice_date}">
                                <i class="fa-solid fa-truck"></i> Transport
                            </button>
                            </td>
                        </tr>
                    `);
                });
            }
        });
    }

    // Fetch Customer Balance Function
    function fetchCustomerBalance(customerCode) {
        $.ajax({
            url: "{{ route('customers.balance') }}",
            type: "GET",
            data: { customer_code: customerCode },
            success: function (res) {
                let field = $('#customer_outstanding_balance');
                field.val(res.outstanding_balance);

                field.removeClass('text-danger text-success fw-bold');
                if (parseFloat(res.outstanding_balance) > 0) {
                    field.addClass('text-danger fw-bold');
                } else if (parseFloat(res.advance) > 0) {
                    field.addClass('text-success fw-bold');
                }
            }
        });
    }

    // Open discount modal
    $(document).on('click', '.edit-discount-btn', function () {
        $('#discount_invoice_id').val($(this).data('id'));
        $('#discount_invoice_no').val($(this).data('invoice-no'));
        $('#discount_invoice_date').val($(this).data('invoice-date'));
        $('#discount_invoice_no_display').val($(this).data('invoice-no'));
        $('#discount_current_balance').val(parseFloat($(this).data('balance')).toFixed(2));
        $('#discount_amount_input').val('');
        $('#discount_error').hide().text('');

        new bootstrap.Modal(document.getElementById('discountModal')).show();
    });

    // Confirm discount submission
    $('#confirm_discount_btn').on('click', function () {
        let amount = parseFloat($('#discount_amount_input').val());

        if (!amount || amount <= 0) {
            $('#discount_error').text('Enter a valid discount amount.').show();
            return;
        }

        let customerCode = $('#selected_customer_code').val();
        let btn = $(this);
        btn.prop('disabled', true).text('Applying...');

        $.ajax({
            url: "{{ route('invoices.apply-discount') }}",
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                invoice_id: $('#discount_invoice_id').val(),
                invoice_no: $('#discount_invoice_no').val(),
                invoice_date: $('#discount_invoice_date').val(),
                customer_code: customerCode,
                discount_amount: amount
            },
            success: function (res) {
                btn.prop('disabled', false).text('Confirm');

                if (!res.success) {
                    $('#discount_error').text(res.message).show();
                    return;
                }

                let row = $(`tr[data-id="${$('#discount_invoice_id').val()}"]`);
                row.find('.discount-cell').text(res.discount);
                row.find('.net-cell').text(res.net_amount);
                let balanceCell = row.find('.balance-cell');
                let stillDue = parseFloat(res.balance) > 0;
                let balanceIconClass = stillDue ? 'fa-triangle-exclamation' : 'fa-circle-check';
                balanceCell.html(`<i class="fa-solid ${balanceIconClass}"></i>${res.balance}`);
                balanceCell.toggleClass('is-due', stillDue);
                balanceCell.toggleClass('is-clear', !stillDue);
                row.find('.edit-discount-btn').data('balance', res.balance);

                bootstrap.Modal.getInstance(document.getElementById('discountModal')).hide();
                toastr.success(res.message);

                // Refresh outstanding balance since a new ledger entry was posted
                fetchCustomerBalance(customerCode);
            },
            error: function (xhr) {
                btn.prop('disabled', false).text('Confirm');
                let msg = xhr.responseJSON?.message || 'Something went wrong.';
                $('#discount_error').text(msg).show();
            }
        });
    });

    // Open transport payment modal
    $(document).on('click', '.transport-payment-btn', function () {
        $('#transport_invoice_id').val($(this).data('id'));
        $('#transport_invoice_no').val($(this).data('invoice-no'));
        $('#transport_invoice_date').val($(this).data('invoice-date'));
        $('#transport_invoice_no_display').val($(this).data('invoice-no'));
        $('#transport_amount_input').val('');
        $('#transport_error').hide().text('');

        new bootstrap.Modal(document.getElementById('transportModal')).show();
    });

    // Confirm transport payment submission
    $('#confirm_transport_btn').on('click', function () {
        let amount = parseFloat($('#transport_amount_input').val());

        if (!amount || amount <= 0) {
            $('#transport_error').text('Enter a valid transport amount.').show();
            return;
        }

        let customerCode = $('#selected_customer_code').val();
        let btn = $(this);
        btn.prop('disabled', true).text('Saving...');

        $.ajax({
            url: "{{ route('invoices.apply-transport') }}",
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                invoice_id: $('#transport_invoice_id').val(),
                invoice_no: $('#transport_invoice_no').val(),
                invoice_date: $('#transport_invoice_date').val(),
                customer_code: customerCode,
                transport_amount: amount
            },
            success: function (res) {
                btn.prop('disabled', false).text('Confirm');

                if (!res.success) {
                    $('#transport_error').text(res.message).show();
                    return;
                }

                bootstrap.Modal.getInstance(document.getElementById('transportModal')).hide();
                toastr.success(res.message);

                // Refresh outstanding balance since a new ledger entry was posted
                fetchCustomerBalance(customerCode);
            },
            error: function (xhr) {
                btn.prop('disabled', false).text('Confirm');
                let msg = xhr.responseJSON?.message || 'Something went wrong.';
                $('#transport_error').text(msg).show();
            }
        });
    });

    // Close autocomplete on click outside
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#customer_search, #customer_list').length) {
            $('#customer_list').hide();
        }
    });
});
</script>
</body>
</html>
@endsection
