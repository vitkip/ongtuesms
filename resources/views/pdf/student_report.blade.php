<!DOCTYPE html>
<html lang="lo">

<head>
    <meta charset="UTF-8">
    <title>ລາຍງານຈຳນວນນັກສຶກສາ</title>
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
            margin: 12mm 16mm 15mm 16mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Phetsarath', sans-serif;
            font-size: 10px;
            line-height: 1.5;
            color: #1a1a2e;
            margin: 0;
            padding: 0;
        }

        /* ===== TOP EMBLEM BLOCK (centered) ===== */
        .emblem-block {
            text-align: center;
            margin-bottom: 3px;
        }

        .emblem-block img {
            width: 68px;
            height: 68px;
            object-fit: contain;
        }

        .gov-name-1 {
            font-size: 12px;
            font-weight: bold;
            color: #000;
            margin: 2px 0 0;
            text-align: center;
        }

        .gov-name-2 {
            font-size: 10px;
            color: #222;
            margin: 1px 0 0;
            text-align: center;
        }

        /* ===== TWO-COLUMN: school info | doc number ===== */
        .org-row {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            margin-bottom: 0;
        }

        .org-left {
            vertical-align: top;
            width: 55%;
            padding-right: 8px;
        }

        .org-right {
            vertical-align: top;
            text-align: right;
            width: 45%;
        }

        .org-ministry {
            font-size: 9.5px;
            color: #333;
            line-height: 1.7;
        }

        .org-school {
            font-size: 10.5px;
            font-weight: bold;
            color: #00327d;
            margin-top: 1px;
        }

        .doc-ref {
            font-size: 9.5px;
            color: #333;
            line-height: 1.8;
        }

        /* ===== DIVIDER ===== */
        .hline {
            border: none;
            border-top: 1.5px solid #00327d;
            margin: 6px 0 0;
        }

        /* ===== REPORT TITLE BOX ===== */
        .title-box {
            text-align: center;
            padding: 7px 0 5px;
            margin: 8px 0 8px;
            border-top: 2px solid #00327d;
            border-bottom: 2px solid #fed65b;
            background-color: #f8faff;
        }

        .title-main {
            font-size: 14px;
            font-weight: bold;
            color: #00327d;
            margin: 0;
        }

        .title-sub {
            font-size: 9px;
            color: #555;
            margin: 2px 0 0;
        }

        /* ===== FILTER NOTE ===== */
        .filter-note {
            font-size: 9px;
            color: #444;
            background: #eef2fb;
            padding: 4px 10px;
            border-left: 3px solid #00327d;
            margin-bottom: 8px;
        }

        /* ===== SUMMARY ===== */
        .summary-total {
            font-size: 10.5px;
            font-weight: bold;
            color: #00327d;
            margin-bottom: 5px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .summary-cell {
            vertical-align: top;
            width: 50%;
            padding-right: 12px;
        }

        .summary-cell:last-child {
            padding-right: 0;
        }

        .summary-head {
            font-size: 9.5px;
            font-weight: bold;
            color: #222;
            border-bottom: 1px solid #c8d3e6;
            padding-bottom: 2px;
            margin-bottom: 3px;
        }

        .summary-row {
            font-size: 9.5px;
            color: #333;
            padding: 1px 0 1px 0;
        }

        /* ===== DATA TABLE ===== */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            margin-bottom: 0;
        }

        .data-table thead tr {
            background-color: #00327d;
            color: #ffffff;
        }

        .data-table thead th {
            padding: 5px 4px;
            text-align: center;
            font-size: 8.5px;
            font-weight: bold;
            border: 0.5px solid #002060;
        }

        .data-table thead th.left {
            text-align: left;
            padding-left: 5px;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f0f5ff;
        }

        .data-table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .data-table tbody td {
            padding: 3.5px 4px;
            border: 0.5px solid #d1d9e8;
            vertical-align: middle;
        }

        .tc {
            text-align: center;
        }

        .tl {
            text-align: left;
            padding-left: 5px;
        }

        .num {
            text-align: center;
            font-weight: bold;
            color: #00327d;
        }

        .tid {
            font-size: 7.5px;
            font-family: monospace;
            color: #00327d;
            font-weight: bold;
        }

        .tname {
            font-weight: bold;
            color: #1a1a2e;
        }

        .tmajor {
            color: #333;
            font-size: 8.5px;
        }

        .tyr {
            text-align: center;
            font-weight: bold;
            color: #00327d;
        }

        /* ===== SIGNATURE (right side) ===== */
        .sig-wrap {
            margin-top: 18px;
            text-align: right;
        }

        .sig-title {
            font-size: 10px;
            font-weight: bold;
        }

        .sig-space {
            height: 38px;
        }

        .sig-name {
            font-size: 9px;
            color: #444;
        }

        /* ===== FOOTER (last page only – regular flow, not fixed) ===== */
        .page-footer {
            margin-top: 14px;
            border-top: 1px solid #c0cce0;
            padding-top: 4px;
            font-size: 8px;
            color: #888;
            width: 100%;
        }

        .pf-table {
            width: 100%;
            border-collapse: collapse;
        }

        .pf-left {
            text-align: left;
        }

        .pf-right {
            text-align: right;
        }
    </style>
</head>

<body>

    <!-- ① National Emblem / School Seal – centered -->
    <div class="emblem-block">
        @if($logoBase64)
            <img src="{{ $logoBase64 }}" alt="ຕາສາ">
        @endif
        <p class="gov-name-1">ສາທາລະນະລັດ ປະຊາທິປະໄຕ ປະຊາຊົນລາວ</p>
        <p class="gov-name-2">ສັນຕິພາບ ເອກະລາດ ປະຊາທິປະໄຕ ເອກະພາບ ວັດທະນາຖາວອນ</p>
    </div>

    <!-- ② Two-column: ministry info (left) | doc number (right) -->
    <table class="org-row">
        <tr>
            <td class="org-left">
                <div class="org-ministry">ກະຊວງສຶກສາທິການ ແລະ ກິລາ</div>
                <div class="org-ministry">ກົມພັດທະນາຄູ ແລະ ການບໍລິຫານການສຶກສາ</div>
                <div class="org-school">{{ $schoolName }}</div>
            </td>
            <td class="org-right">
                <div class="doc-ref">ເລກທີ: ...../ວຄສ.ອຕ</div>
                <div class="doc-ref">ນະຄອນຫຼວງວຽງຈັນ, {{ $printDate }}</div>
            </td>
        </tr>
    </table>

    <hr class="hline">

    <!-- ③ Report Title -->
    <div class="title-box">
        <p class="title-main">{{ $reportTitle }}</p>
        @if($filterDescription)
            <p class="title-sub">{{ $filterDescription }}</p>
        @endif
    </div>

    <!-- ④ Filter note (only when filters applied) -->
    @if($filterDescription)
        <div class="filter-note">
            ເງື່ອນໄຂ: {{ $filterDescription }} &nbsp;|&nbsp; ລວມທັງໝົດ: <strong>{{ $students->count() }} ຄົນ</strong>
        </div>
    @endif

    <!-- ⑤ Summary -->
    <div class="summary-total">ຈຳນວນນັກສຶກສາທັງໝົດ: {{ $students->count() }} ອົງ</div>
    <table class="summary-table">
        <tr>
            <td class="summary-cell">
                <div class="summary-head">ຈຳນວນນັກສຶກສາແຍກຕາມສາຂາວິຊາ:</div>
                @foreach($byMajor as $majorName => $count)
                    <div class="summary-row">• {{ $majorName }}: {{ $count }} ອົງ</div>
                @endforeach
            </td>
            <td class="summary-cell">
                <div class="summary-head">ຈຳນວນນັກສຶກສາແຍກຕາມປີຮຽນ:</div>
                @foreach($byYearLevel as $yl => $cnt)
                    <div class="summary-row">• ປີ {{ $yl }}: {{ $cnt }} ອົງ</div>
                @endforeach
            </td>
        </tr>
    </table>

    <!-- ⑥ Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:26px">ລຳດັບ</th>
                <th style="width:88px" class="left">ລະຫັດ</th>
                <th style="width:30px">ເພດ</th>
                <th class="left">ຊື່ - ນາມສະກຸນ</th>
                <th style="width:56px">ວັນເກີດ</th>
                <th style="width:88px">ສາຂາວິຊາ</th>
                <th style="width:33px">ປີຮຽນ</th>
                <th style="width:70px">ໂທລະສັບ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
                <tr>
                    <td class="num">{{ $index + 1 }}</td>
                    <td class="tid tl">{{ $student->student_id ?? '-' }}</td>
                    <td class="tc">{{ $student->gender }}</td>
                    <td class="tname tl">{{ $student->first_name }} {{ $student->last_name }}</td>
                    <td class="tc">{{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('d/m/Y') : '-' }}</td>
                    <td class="tmajor tc">{{ $student->major->name ?? '-' }}</td>
                    <td class="tyr">ປີ {{ $student->year_level ?? '-' }}</td>
                    <td class="tc">{{ $student->phone ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- ⑦ Signature (right-aligned, after table) -->
    <div class="sig-wrap">
        <div class="sig-title">ຫົວໜ້າພະແນກວິຊາການ</div>
        <div class="sig-space"></div>
        <div class="sig-name">( .......................................................... )</div>
    </div>

    <!-- ⑧ Footer – last page only (regular document flow, not fixed) -->
    <div class="page-footer">
        <table class="pf-table">
            <tr>
                <td class="pf-left">
                    ລາຍງານໂດຍ: ພະແນກວິຊາການ {{ $schoolName }} &nbsp;|&nbsp;
                    Tel: {{ setting('school_phone', '+856-20-9121-3388') }} &nbsp;
                    Email: {{ setting('school_email', 'info@ongtue-ttc.edu.la') }},
                    www.ongtue-ttc.edu.la
                </td>
                <td class="pf-right">ລາຍງານ {{ $printDate }} ເວລາ {{ now()->format('H:i:s') }}</td>
            </tr>
        </table>
    </div>

</body>

</html>