@props(['title', 'fromDate' => null, 'toDate' => null, 'companyData' => null, 'branchDel' => null])
<script>
    window.stockReportPrintOptions = {{ Illuminate\Support\Js::from([
        'title' => $title,
        'fromDate' => $fromDate,
        'toDate' => $toDate,
        'company' => $companyData->name ?? '',
        'branch' => $branchDel->name ?? '',
        'logo' => asset('assets/images/image.jpg'),
    ]) }};
</script>
<script src="{{ asset('assets/js/report-print.js') }}"></script>
