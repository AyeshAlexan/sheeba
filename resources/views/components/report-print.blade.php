@props(['title', 'fromDate' => null, 'toDate' => null, 'landscape' => false])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Segoe UI", Tahoma, Arial, sans-serif; font-size: 12px; color: #172033; background: #F6F8FC; }

        .rp-toolbar {
            display: flex; align-items: center; gap: 10px;
            padding: 14px 24px; background: #fff; border-bottom: 1px solid #E5EAF2;
        }
        .rp-btn {
            display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px;
            border: 1px solid #E5EAF2; border-radius: 999px; cursor: pointer;
            font-size: 13px; font-weight: 600; font-family: inherit; background: #fff; color: #14213D;
        }
        .rp-btn.primary { background: #1677FF; border-color: #1677FF; color: #fff; }

        .rp-sheet { max-width: 1000px; margin: 24px auto; background: #fff; padding: 28px 32px; border: 1px solid #E5EAF2; border-radius: 10px; }

        .rp-letterhead { display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; border-bottom: 2px solid #1677FF; padding-bottom: 14px; margin-bottom: 10px; }
        .rp-left { display: flex; align-items: center; gap: 12px; }
        .rp-left img { height: 52px; width: auto; object-fit: contain; }
        .rp-company-name { font-size: 15px; font-weight: 700; color: #14213D; }
        .rp-branch { font-size: 11.5px; color: #667085; margin-top: 2px; }
        .rp-date { font-size: 11px; color: #98A2B3; margin-top: 2px; }

        .rp-middle { text-align: center; flex: 1; }
        .rp-title { font-size: 20px; font-weight: 700; color: #14213D; letter-spacing: .2px; }

        .rp-period { text-align: center; font-size: 12.5px; color: #667085; margin-bottom: 18px; }
        .rp-period strong { color: #14213D; }

        table { width: 100%; border-collapse: collapse; }
        .rp-wide-table { table-layout: fixed; font-size: 8px; }
        .rp-wide-table th, .rp-wide-table td { padding: 4px 5px; overflow-wrap: anywhere; line-height: 1.15; }
        .rp-wide-table th { white-space: normal !important; word-break: break-all; }
        .rp-wide-table th:nth-child(1), .rp-wide-table td:nth-child(1) { width: 7%; }
        .rp-wide-table th:nth-child(2), .rp-wide-table td:nth-child(2) { width: 14%; }
        .rp-wide-table th:last-child, .rp-wide-table td:last-child { width: 8%; }
        .rp-numeric { text-align: right !important; white-space: nowrap; }
        .rp-sub-table { margin-top: 4px; }
        .rp-sub-table thead th {
            background: #EAF3FF; color: #14213D; border-color: #E5EAF2;
            -webkit-print-color-adjust: exact; print-color-adjust: exact;
        }
        .rp-sub-table tbody td { font-size: 10px; padding: 4px 6px; }
        thead th {
            background: #14213D; color: #fff; font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .03em; padding: 9px 10px; border: 1px solid #14213D; text-align: left;
            -webkit-print-color-adjust: exact; print-color-adjust: exact;
        }
        tbody td { padding: 7px 10px; font-size: 12px; border: 1px solid #E5EAF2; vertical-align: middle; }
        tbody tr:nth-child(even) td { background: #F6F8FC; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        tfoot td {
            background: #EAF3FF; font-weight: 700; padding: 8px 10px; border: 1px solid #E5EAF2; color: #14213D;
            -webkit-print-color-adjust: exact; print-color-adjust: exact;
        }
        caption { caption-side: top; text-align: left; font-weight: 600; margin-bottom: 6px; color: #14213D; }

        @media print {
            .rp-toolbar { display: none !important; }
            body { background: #fff; }
            .rp-sheet { margin: 0; border: none; border-radius: 0; max-width: 100%; }
            @page { size: A4 {{ $landscape ? 'landscape' : '' }}; margin: {{ $landscape ? '10mm' : '12mm' }}; }
        }
    </style>
</head>
<body>

    <div class="rp-toolbar">
        <button type="button" class="rp-btn primary" onclick="window.print()">Print</button>
        <button type="button" class="rp-btn" onclick="window.close()">Close</button>
    </div>

    <div class="rp-sheet">
        <div class="rp-letterhead">
            <div class="rp-left">
                <img src="{{ asset('assets/images/image.jpg') }}" alt="Logo">
                <div>
                    <div class="rp-company-name">{{ $companyData->name ?? '' }}</div>
                    <div class="rp-branch">{{ $branchDel->name ?? '' }}</div>
                    <time class="rp-date" id="rp-local-time"></time>
                </div>
            </div>
            <div class="rp-middle">
                <div class="rp-title">{{ $title }}</div>
            </div>
            <div style="width:52px;"></div>
        </div>

        <div class="rp-period">
            @if($fromDate && $toDate)
                Period: <strong>{{ $fromDate }}</strong> &nbsp;→&nbsp; <strong>{{ $toDate }}</strong>
            @else
                Period: <strong>All Data</strong>
            @endif
        </div>

        {{ $slot }}
    </div>

    <script>
        const localTime = new Date();
        const localTimeParts = new Intl.DateTimeFormat('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        }).formatToParts(localTime).reduce((parts, part) => {
            parts[part.type] = part.value;
            return parts;
        }, {});

        document.getElementById('rp-local-time').textContent =
            `${localTimeParts.day} ${localTimeParts.month} ${localTimeParts.year}, ` +
            `${localTimeParts.hour}:${localTimeParts.minute} ${localTimeParts.dayPeriod.toUpperCase()}`;
    </script>
</body>
</html>
