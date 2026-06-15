<!DOCTYPE html>
<html lang="lo">

<head>
    <meta charset="UTF-8">
    <title>ລາຍງານລາຍຮັບ ແລະ ໃບບິນ</title>
    <style>
        @font-face {
            font-family: 'Phetsarath';
            font-style: normal;
            font-weight: normal;
            src: url("{{ storage_path('fonts/Phetsarath-Regular.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'Phetsarath';
            font-style: normal;
            font-weight: bold;
            src: url("{{ storage_path('fonts/Phetsarath-Bold.ttf') }}") format('truetype');
        }

        @page {
            size: A4 portrait;
            margin: 15mm 15mm 20mm 15mm;
        }

        body {
            font-family: 'Phetsarath', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Phetsarath', sans-serif;
            margin: 0 0 5px 0;
            color: #0f172a;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .header-logo {
            width: 65px;
            vertical-align: top;
        }

        .header-logo img {
            width: 60px;
            height: auto;
        }

        .header-text {
            vertical-align: top;
            padding-left: 15px;
        }

        .school-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e3a8a;
        }

        .school-subtitle {
            font-size: 9px;
            color: #64748b;
            margin-top: 2px;
            line-height: 1.4;
        }

        .report-title-container {
            text-align: center;
            background-color: #f8fafc;
            border-top: 2px solid #1e3a8a;
            border-bottom: 2px solid #b45309;
            padding: 8px 0;
            margin-bottom: 15px;
        }

        .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e3a8a;
        }

        .filter-description {
            font-size: 9.5px;
            color: #475569;
            margin-bottom: 12px;
            background-color: #f1f5f9;
            padding: 6px 12px;
            border-radius: 4px;
        }

        .summary-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .summary-card {
            border: 1px solid #e2e8f0;
            padding: 8px 10px;
            text-align: center;
            background-color: #ffffff;
            border-radius: 4px;
        }

        .summary-card.paid {
            border-left: 3px solid #10b981;
            background-color: #f0fdf4;
        }

        .summary-card.unpaid {
            border-left: 3px solid #f59e0b;
            background-color: #fdfbeb;
        }

        .summary-card.cancelled {
            border-left: 3px solid #ef4444;
            background-color: #fef2f2;
        }

        .summary-title {
            font-size: 8.5px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
        }

        .summary-value {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 2px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .items-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-family: 'Phetsarath', sans-serif;
            font-weight: bold;
            padding: 6px 8px;
            font-size: 9.5px;
            border: 1px solid #1e3a8a;
            text-align: left;
        }

        .items-table td {
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
            color: #334155;
            font-size: 9px;
        }

        .items-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .status-badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 8px;
        }

        .status-paid {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-unpaid {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .signature-col {
            width: 50%;
            vertical-align: top;
            text-align: center;
        }

        .signature-title {
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 60px;
        }

        .signature-line {
            color: #475569;
        }

        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td class="header-logo">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Seal of Ongtue College" />
                @endif
            </td>
            <td class="header-text">
                <div class="school-title">{{ $schoolName }}</div>
                <div class="school-subtitle">
                    {{ $schoolAddress }} <br>
                    ເບີໂທ: {{ $schoolPhone }} | ອີເມລ: {{ $schoolEmail }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Report Title -->
    <div class="report-title-container">
        <div class="report-title">ລາຍງານສະຫຼຸບລາຍຮັບ ແລະ ໃບບິນ (INVOICE REPORT)</div>
    </div>

    <!-- Active Filters -->
    <div class="filter-description">
        <strong>ເງື່ອນໄຂການດຶງລາຍງານ:</strong>
        ສະຖານະ:
        {{ $status ? match ($status) { 'paid' => 'ຊຳລະແລ້ວ', 'unpaid' => 'ຍັງບໍ່ຊຳລະ', 'cancelled' => 'ຍົກເລີກ', default => $status} : 'ທັງໝົດ' }}
        @if($search) | ຄຳຄົ້ນຫາ: "{{ $search }}" @endif
        | ວັນທີອອກລາຍງານ: {{ $printDate }}
    </div>

    <!-- Summary Box Grid -->
    <table class="summary-grid">
        <tr>
            <td style="padding-right: 8px; width: 25%;">
                <div class="summary-card">
                    <div class="summary-title">ໃບບິນທັງໝົດ</div>
                    <div class="summary-value">{{ number_format($summary['total_count']) }} ໃບບິນ</div>
                </div>
            </td>
            <td style="padding-right: 8px; width: 25%;">
                <div class="summary-card paid">
                    <div class="summary-title" style="color: #10b981;">ຊຳລະແລ້ວ (ລາຍຮັບ)</div>
                    <div class="summary-value" style="color: #047857;">{{ number_format($summary['paid_amount']) }} ກີບ
                    </div>
                </div>
            </td>
            <td style="padding-right: 8px; width: 25%;">
                <div class="summary-card unpaid">
                    <div class="summary-title" style="color: #f59e0b;">ຍັງບໍ່ຊຳລະ</div>
                    <div class="summary-value" style="color: #b45309;">{{ number_format($summary['unpaid_amount']) }}
                        ກີບ</div>
                </div>
            </td>
            <td style="width: 25%;">
                <div class="summary-card cancelled">
                    <div class="summary-title" style="color: #ef4444;">ຍົກເລີກ</div>
                    <div class="summary-value" style="color: #b91c1c;">{{ number_format($summary['cancelled_amount']) }}
                        ກີບ</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">ລຳດັບ</th>
                <th style="width: 15%;">ເລກໃບບິນ</th>
                <th style="width: 30%;">ນັກສຶກສາ / ລະຫັດ</th>
                <th style="width: 15%;">ສາຂາວິຊາ</th>
                <th style="width: 10%; text-align: center;">ວັນທີອອກບິນ</th>
                <th style="width: 15%; text-align: right;">ຍອດລວມ (LAK)</th>
                <th style="width: 10%; text-align: center;">% ສະຖານະ</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $idx => $invoice)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td style="font-family: monospace; font-weight: bold; color: #1e3a8a;">{{ $invoice->invoice_number }}
                    </td>
                    <td>
                        <strong>{{ $invoice->student->gendered_name }}</strong> <br>
                        <span style="font-size: 8px; color: #64748b;">{{ $invoice->student->student_id ?? '-' }}</span>
                    </td>
                    <td>{{ $invoice->student->major->name ?? 'N/A' }}</td>
                    <td class="text-center">{{ $invoice->date ? $invoice->date->format('d/m/Y') : '—' }}</td>
                    <td class="text-right" style="font-weight: bold;">{{ number_format($invoice->total_amount) }}</td>
                    <td class="text-center">
                        @if($invoice->payment_status === 'paid')
                            <span class="status-badge status-paid">ຊຳລະແລ້ວ</span>
                        @elseif($invoice->payment_status === 'unpaid')
                            <span class="status-badge status-unpaid">ຍັງບໍ່ຊຳລະ</span>
                        @else
                            <span class="status-badge status-cancelled">ຍົກເລີກ</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px; color: #64748b;">
                        ບໍ່ມີຂໍ້ມູນໃບບິນທີ່ກົງກັບເງື່ອນໄຂ</td>
                </tr>
            @endforelse
            @if($invoices->isNotEmpty())
                <tr style="background-color: #f1f5f9; font-weight: bold;">
                    <td colspan="5" class="text-right" style="color: #1e3a8a; font-size: 9.5px; padding: 8px;">ຍອດລວມທັງໝົດ
                        (TOTAL AMOUNT):</td>
                    <td class="text-right" style="color: #b45309; font-size: 9.5px; padding: 8px;">
                        {{ number_format($summary['total_amount']) }}
                    </td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Bottom Signatures -->
    <table class="signature-table">
        <tr>
            <td class="signature-col">
                <div class="signature-title">ຫົວໜ້າພະແນກບໍລິການ ແລະ ການເງິນ</div>
            </td>
            <td class="signature-col">
                <div class="signature-title">ພະນັກງານການເງີນ </div>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        ວິທະຍາໄລຄູສົງ ອົງຕື້ - ພິມໂດຍລະບົບ Ongtue SMS ເວລາ {{ date('H:i:s d/m/Y') }}
    </div>

</body>

</html>