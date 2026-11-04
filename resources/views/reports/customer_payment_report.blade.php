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
                <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
                <title>Customer Payment Report</title>
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">
            </head>

            <body>

        <div class="main-wrapper">
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <div class="page-header ph-flex">
                        <div class="ph-left">
                            <div class="ph-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            </div>
                            <div>
                                <h3 class="page-title">Customer Payment Report</h3>
                                <p class="page-subtitle">Every customer payment — cash, cheque, card, and bank — with running totals.</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary" onclick="printTablefun()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>

                    <div class="container-fluid px-0">
                        <div class="card">
                            <div class="card-body">
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                                </div>
                                @endif

                                <form action="{{ route('customer_payment_report_by_date') }}" method="GET" class="row g-2 mb-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">From Date</label>
                                        <input type="date" name="from_date" id="from_date" class="form-control" value="{{ $fromDate }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">To Date</label>
                                        <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">Customer Code</label>
                                        <input type="text" name="customer" class="form-control" value="{{ $customer }}" placeholder="Customer code">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                                </form>

                                @if($fromDate && $toDate)
                                <p class="text-muted small mb-2">{{ $fromDate }} &nbsp;→&nbsp; {{ $toDate }}</p>
                                @endif

                                <div class="modern-table-card">
                                    <div class="table-responsive">
                                        <table class="table" id="t_item_movements" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>Payment No</th>
                                                    <th>Payment Date</th>
                                                    <th>Sales No</th>
                                                    <th>Customer Code</th>
                                                    <th>Name</th>
                                                    <th>Phone</th>
                                                    <th>Note</th>
                                                    <th>Amount</th>
                                                    <th>Cash</th>
                                                    <th>Cheque</th>
                                                    <th>Card</th>
                                                    <th>Bank</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $totalAmount = 0; $totalCashAmount = 0; $totalChequeAmount = 0; $totalCardAmount = 0; $totalBankAmount = 0;
                                                @endphp
                                                @forelse ($paymentDetails as $data)
                                                <tr class="payment-row" style="cursor:pointer" data-id="{{ $data->id }}" title="Click for details">
                                                    <td>{{ $data->Payment_no }}</td>
                                                    <td>{{ $data->Payment_date }}</td>
                                                    <td>{{ $data->Sales_no }}</td>
                                                    <td>{{ $data->Customer_Code }}</td>
                                                    <td>{{ $data->Customer_Name }}</td>
                                                    <td>{{ $data->Customer_Phone }}</td>
                                                    <td>{{ $data->Payment_note }}</td>
                                                    <td class="text-end">{{ number_format($data->Payment_Amount, 2) }}</td>
                                                    <td class="text-end">{{ number_format($data->cash_payment, 2) }}</td>
                                                    <td class="text-end">{{ number_format($data->cheque_payment, 2) }}</td>
                                                    <td class="text-end">{{ number_format($data->card_payment, 2) }}</td>
                                                    <td class="text-end">{{ number_format($data->bank_transfer, 2) }}</td>
                                                </tr>
                                                @php
                                                    $totalAmount += $data->Payment_Amount;
                                                    $totalCashAmount += $data->cash_payment;
                                                    $totalChequeAmount += $data->cheque_payment;
                                                    $totalCardAmount += $data->card_payment;
                                                    $totalBankAmount += $data->bank_transfer;
                                                @endphp
                                                @empty
                                                <tr><td colspan="12" class="text-center text-muted">No payments found.</td></tr>
                                                @endforelse
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="7" class="text-end">Total Balance</td>
                                                    <td class="text-end">{{ number_format($totalAmount, 2) }}</td>
                                                    <td class="text-end">{{ number_format($totalCashAmount, 2) }}</td>
                                                    <td class="text-end">{{ number_format($totalChequeAmount, 2) }}</td>
                                                    <td class="text-end">{{ number_format($totalCardAmount, 2) }}</td>
                                                    <td class="text-end">{{ number_format($totalBankAmount, 2) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div id="customerPaymentCustomPager"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment detail popup --}}
                <div class="modal fade" id="paymentDetailModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Payment Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body" id="paymentDetailBody">
                                <div class="text-center text-muted py-4">Loading...</div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('layouts.footer')
            </div>
         </div>

<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/js/dt-custom-pager.js"></script>

<script>
jQuery(document).ready(function ($) {
    // If we arrived here from a "which payment was this?" link (e.g.
    // Cash & Cheque Transaction report), filter straight down to
    // payments matching that sales/customer reference.
    let urlParams = new URLSearchParams(window.location.search);
    let jumpToPayment = urlParams.get('payment');

    var customerPaymentTable = $('#t_item_movements').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'csv', 'pdf'],
        pageLength: 15,
        lengthChange: false,
        search: jumpToPayment ? { search: jumpToPayment } : undefined,
    });

    $('#t_item_movements_wrapper').addClass('dt-collapsed');
    DTCustomPager.init(customerPaymentTable, '#customerPaymentCustomPager');

    var toDateInput = document.getElementById('to_date');
    if (!toDateInput.value) {
        var dateObj = new Date();
        toDateInput.value = dateObj.toISOString().slice(0, 10);
    }

    // Row click -> payment detail popup (breakdown + any linked cheques)
    $('#t_item_movements tbody').on('click', 'tr.payment-row', function () {
        let id = $(this).data('id');
        let $body = $('#paymentDetailBody');
        $body.html('<div class="text-center text-muted py-4">Loading...</div>');
        let modal = new bootstrap.Modal(document.getElementById('paymentDetailModal'));
        modal.show();

        $.ajax({
            url: "{{ route('customer_payment_report.details') }}",
            method: 'GET',
            data: { id: id },
            success: function (res) {
                if (res.status !== 'success') {
                    $body.html('<div class="text-center text-muted py-4">Payment not found.</div>');
                    return;
                }
                let p = res.payment;
                let html = `
                    <div class="row g-2 mb-3">
                        <div class="col-md-4"><strong>Payment No:</strong> ${p.Payment_no ?? ''}</div>
                        <div class="col-md-4"><strong>Payment Date:</strong> ${p.Payment_date ?? ''}</div>
                        <div class="col-md-4"><strong>Sales No:</strong> ${p.Sales_no ?? ''}</div>
                        <div class="col-md-4"><strong>Customer:</strong> ${p.Customer_Name ?? ''} (${p.Customer_Code ?? ''})</div>
                        <div class="col-md-4"><strong>Phone:</strong> ${p.Customer_Phone ?? ''}</div>
                        <div class="col-md-4"><strong>Note:</strong> ${p.Payment_note ?? ''}</div>
                    </div>
                    <table class="table table-bordered table-sm mb-3">
                        <thead class="thead-light"><tr>
                            <th>Cash</th><th>Card</th><th>Bank Transfer</th><th>Cheque</th><th>Total</th>
                        </tr></thead>
                        <tbody><tr>
                            <td>${parseFloat(p.cash_payment ?? 0).toFixed(2)}</td>
                            <td>${parseFloat(p.card_payment ?? 0).toFixed(2)}</td>
                            <td>${parseFloat(p.bank_transfer ?? 0).toFixed(2)}</td>
                            <td>${parseFloat(p.cheque_payment ?? 0).toFixed(2)}</td>
                            <td><strong>${parseFloat(p.Payment_Amount ?? 0).toFixed(2)}</strong></td>
                        </tr></tbody>
                    </table>`;

                if (res.cheques && res.cheques.length) {
                    html += `<h6>Cheque Details</h6>
                        <table class="table table-bordered table-sm">
                            <thead class="thead-light"><tr>
                                <th>Bank</th><th>Other Bank</th><th>Account No</th><th>Cheque No</th><th>Amount</th><th>Status</th>
                            </tr></thead><tbody>`;
                    res.cheques.forEach(function (c) {
                        html += `<tr>
                            <td>${c.bank ?? ''}</td>
                            <td>${c.other_bank_name ?? ''}</td>
                            <td>${c.acc_no ?? ''}</td>
                            <td>${c.cheques_no ?? ''}</td>
                            <td>${parseFloat(c.amount ?? 0).toFixed(2)}</td>
                            <td>${c.cheque_status ?? ''}</td>
                        </tr>`;
                    });
                    html += `</tbody></table>`;
                } else if (parseFloat(p.cheque_payment ?? 0) > 0) {
                    html += `<p class="text-muted small">Cheque amount recorded, but no matching cheque rows found.</p>`;
                }

                $body.html(html);
            },
            error: function (err) {
                $body.html('<div class="text-center text-danger py-4">Failed to load details (' + err.status + ').</div>');
            }
        });
    });
});

function printTablefun() {
    var params = $('form[action="{{ route('customer_payment_report_by_date') }}"]').serialize();
    window.open('{{ route('customer_payment_report.print') }}?' + params, '_blank');
}
</script>

</body>
@endsection

</html>
