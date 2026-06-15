<!DOCTYPE html>
<html lang="lo">

<head>
    <meta charset="UTF-8">
    <title>ໃບບິນຮັບເງິນ - {{ $invoice->invoice_number }}</title>
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
            size: A4;
            margin: 15mm 15mm 20mm 15mm;
        }

        body {
            font-family: 'Phetsarath', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #1e293b; /* slate-800 */
            margin: 0;
            padding: 0;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Phetsarath', sans-serif;
            margin: 0 0 5px 0;
            color: #0f172a; /* slate-900 */
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .header-logo {
            width: 70px;
            vertical-align: top;
        }

        .header-logo img {
            width: 65px;
            height: auto;
        }

        .header-text {
            vertical-align: top;
            padding-left: 15px;
        }

        .school-title {
            font-size: 18px;
            font-weight: bold;
            color: #1e3a8a; /* deep navy */
        }

        .school-subtitle {
            font-size: 10px;
            color: #64748b; /* slate-500 */
            margin-top: 3px;
            line-height: 1.5;
        }

        .invoice-title-container {
            text-align: center;
            background-color: #f8fafc;
            border-top: 2px solid #1e3a8a;
            border-bottom: 2px solid #b45309; /* amber-700 accent */
            padding: 8px 0;
            margin-bottom: 20px;
        }

        .invoice-title {
            font-size: 18px;
            font-weight: bold;
            color: #1e3a8a;
            letter-spacing: 1px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .info-table td {
            padding: 12px 15px;
            vertical-align: top;
            width: 50%;
        }

        .info-item {
            margin-bottom: 6px;
        }
        
        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-weight: bold;
            color: #475569; /* slate-600 */
            width: 95px;
            display: inline-block;
        }

        .info-value {
            color: #0f172a; /* slate-900 */
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
            padding: 8px 12px;
            font-size: 11px;
            border: 1px solid #1e3a8a;
            text-align: left;
        }

        .items-table td {
            padding: 9px 12px;
            border: 1px solid #e2e8f0;
            color: #334155;
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

        .total-row td {
            font-weight: bold;
            font-size: 12px;
            border-top: 2px solid #1e3a8a !important;
            border-bottom: 2px solid #1e3a8a !important;
            background-color: #f1f5f9 !important;
            color: #0f172a;
        }

        .total-label {
            font-family: 'Phetsarath', sans-serif;
            color: #1e3a8a;
        }

        .total-amount {
            color: #b45309; /* amber-700 accent for the total amount */
        }

        .notes-row td {
            font-size: 10px;
            color: #475569;
            background-color: #ffffff !important;
            padding: 10px 12px;
        }

        .bottom-container {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .qr-section {
            width: 45%;
            vertical-align: top;
            border: 1px dashed #b45309;
            padding: 15px;
            border-radius: 6px;
            background-color: #fdfbf7; /* warm tint */
        }

        .qr-title {
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 10px;
            text-align: center;
            font-size: 11px;
        }

        .qr-image {
            text-align: center;
            margin-bottom: 10px;
        }

        .qr-image img {
            width: 120px;
            height: 120px;
            display: inline-block;
        }

        .qr-note {
            font-size: 8.5px;
            color: #475569;
            text-align: center;
            line-height: 1.4;
        }

        .signature-section {
            width: 55%;
            vertical-align: top;
            padding-left: 40px;
        }

        .signature-date {
            font-size: 11px;
            color: #0f172a;
            margin-bottom: 12px;
            text-align: right;
        }

        .signature-title {
            text-align: center;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 70px;
        }

        .signature-line {
            text-align: center;
            color: #475569;
            font-weight: bold;
        }

        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 8.5px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 10px;
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

    <!-- Invoice Title -->
    <div class="invoice-title-container">
        <div class="invoice-title">ໃບບິນຮັບເງິນ / RECEIPT & INVOICE</div>
    </div>

    <!-- Info Section -->
    <table class="info-table">
        <tr>
            <td>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 95px; font-weight: bold; color: #475569; padding: 3px 0; border: none; background: transparent;">ລະຫັດນັກສຶກສາ:</td>
                        <td style="color: #0f172a; padding: 3px 0; font-weight: bold; border: none; background: transparent;">{{ $invoice->student->student_id ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="width: 95px; font-weight: bold; color: #475569; padding: 3px 0; border: none; background: transparent;">ຊື່ ແລະ ນາມສະກຸນ:</td>
                        <td style="color: #0f172a; padding: 3px 0; border: none; background: transparent;">{{ $invoice->student->gendered_name }}</td>
                    </tr>
                    <tr>
                        <td style="width: 95px; font-weight: bold; color: #475569; padding: 3px 0; border: none; background: transparent;">ສາຂາວິຊາ:</td>
                        <td style="color: #0f172a; padding: 3px 0; border: none; background: transparent;">{{ $invoice->student->major->name ?? 'N/A' }}</td>
                    </tr>
                </table>
            </td>
            <td style="border-left: 1px solid #e2e8f0; padding-left: 20px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 90px; font-weight: bold; color: #475569; padding: 3px 0; border: none; background: transparent;">ເລກທີໃບບິນ:</td>
                        <td style="color: #1e3a8a; padding: 3px 0; font-weight: bold; border: none; background: transparent;">{{ $invoice->invoice_number }}</td>
                    </tr>
                    <tr>
                        <td style="width: 90px; font-weight: bold; color: #475569; padding: 3px 0; border: none; background: transparent;">ວັນທີອອກບິນ:</td>
                        <td style="color: #0f172a; padding: 3px 0; border: none; background: transparent;">{{ $invoice->date ? $invoice->date->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td style="width: 90px; font-weight: bold; color: #475569; padding: 3px 0; border: none; background: transparent;">ສະຖານະຊຳລະ:</td>
                        <td style="padding: 3px 0; border: none; background: transparent;">
                            @if($invoice->payment_status === 'paid')
                                <span class="status-badge status-paid">ຊຳລະແລ້ວ</span>
                            @elseif($invoice->payment_status === 'unpaid')
                                <span class="status-badge status-unpaid">ຍັງບໍ່ຊຳລະ</span>
                            @else
                                <span class="status-badge status-cancelled">ຍົກເລີກ</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 8%; text-align: center;">ລຳດັບ</th>
                <th>ລາຍການຄ່າທຳນຽມ (Fee Description)</th>
                <th style="width: 30%; text-align: right;">ຈຳນວນເງິນ (Amount LAK)</th>
            </tr>
        </thead>
        <tbody>
            @php $itemNo = 1; @endphp
            @if($invoice->card_fee > 0)
                <tr>
                    <td class="text-center">{{ $itemNo++ }}</td>
                    <td>ຄ່າທຳນຽມເຮັດບັດນັກສຶກສາ (Student ID Card Fee)</td>
                    <td class="text-right">{{ number_format($invoice->card_fee) }} ກີບ</td>
                </tr>
            @endif
            @if($invoice->photo_fee > 0)
                <tr>
                    <td class="text-center">{{ $itemNo++ }}</td>
                    <td>ຄ່າຮູບຖ່າຍຕິດບັດ ແລະ ຟອມຕ່າງໆ (ID Photo Fee)</td>
                    <td class="text-right">{{ number_format($invoice->photo_fee) }} ກີບ</td>
                </tr>
            @endif
            @if($invoice->email_fee > 0)
                <tr>
                    <td class="text-center">{{ $itemNo++ }}</td>
                    <td>ຄ່າບຳລຸງຮັກສາລະບົບອີເມລວິທະຍາໄລ (College Email Account Fee)</td>
                    <td class="text-right">{{ number_format($invoice->email_fee) }} ກີບ</td>
                </tr>
            @endif
            @if($invoice->tuition_fee > 0)
                <tr>
                    <td class="text-center">{{ $itemNo++ }}</td>
                    <td>ຄ່າຮຽນ ແລະ ຄ່າລົງທະບຽນປະຈຳພາກຮຽນ (Tuition Registration Fee)</td>
                    <td class="text-right">{{ number_format($invoice->tuition_fee) }} ກີບ</td>
                </tr>
            @endif

            @if($invoice->notes)
                <tr class="notes-row">
                    <td colspan="3">
                        <strong style="color: #475569;">ໝາຍເຫດ (Remarks):</strong> <span style="font-style: italic;">{{ $invoice->notes }}</span>
                    </td>
                </tr>
            @endif

            <tr class="total-row">
                <td colspan="2" class="total-label text-right">ຍອດລວມທີ່ຕ້ອງຊຳລະທັງໝົດ (TOTAL AMOUNT DUE):</td>
                <td class="total-amount text-right">{{ number_format($invoice->total_amount) }} ກີບ</td>
            </tr>
        </tbody>
    </table>

    <!-- Bottom Section -->
    <table class="bottom-container">
        <tr>
            <!-- QR Section -->
            <td class="qr-section">
                <div class="qr-title">ສະແກນເພື່ອໂອນເງິນ / Scan to Pay</div>
                <div class="qr-image">
                    @if($qrBase64)
                        <img src="{{ $qrBase64 }}" alt="Payment QR Code" />
                    @else
                        <div style="height: 120px; line-height: 120px; text-align: center; color: #94a3b8; font-size: 10px; border: 1px dashed #cbd5e1; background: #fff;">
                            [ ບໍ່ມີ QR Code ]
                        </div>
                    @endif
                </div>
                <div class="qr-note">
                    ທະນາຄານການຄ້າຕ່າງປະເທດລາວ (BCEL) <br>
                    เลກບັນຊີ: <strong style="color: #b45309; font-size: 10px;">{{ $invoice->bank_account_number }}</strong> <br>
                    ຊື່ບັນຊີ: ວິທະຍາໄລຄູສົງ ອົງຕື້
                </div>
            </td>

            <!-- Signature Section -->
            <td class="signature-section">
                <div class="signature-date">ນະຄອນຫຼວງວຽງຈັນ, ວັນທີ......./......./.......</div>
                <div class="signature-title">ຫົວໜ້າພະແນກບໍລິການ ແລະ ການເງິນ</div>
                <div class="signature-line">(...................................................)</div>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        ຂອບໃຈທີ່ຊຳລະຄ່າທຳນຽມຕາມກຳນົດເວລາ | ພິມໂດຍລະບົບ Ongtue SMS ເວລາ {{ date('H:i d/m/Y') }}
    </div>

</body>

</html>