<!DOCTYPE html>
<html lang="lo">

<head>
    <meta charset="UTF-8" />
    <title>ບັດນັກສຶກສາ - {{ $student->student_id }}</title>
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
            margin: 0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Phetsarath', sans-serif;
            width: 210mm;
            background: #ffffffff;
        }

        /* ──────────────────────────────────────
           A4 PAGE WRAPPER
        ────────────────────────────────────── */
        .page {
            width: 210mm;
            height: 297mm;
            background: #ffffffff;
            position: relative;
        }

        /* Page break only after page 1 */
        .page-front {
            page-break-after: always;
        }

        /* ──────────────────────────────────────
           CR80 CARD  86 × 54 mm
           Centred on A4 — symmetric for both
           long-edge & short-edge duplex flip
           left  = (210 - 86) / 2 = 62 mm
           top   = (297 - 54) / 2 = 121.5 mm
        ────────────────────────────────────── */
        .card {
            position: absolute;
            top: 121.5mm;
            left: 62mm;
            width: 86mm;
            height: 54mm;
            border: 0.7pt solid #1e2d7d;
            border-radius: 2.5mm;
            background: #ffffff;
            overflow: hidden;
        }

        /* ── Crop / cut marks (same on both pages) ── */
        /* Each mark: 5 mm long, 1 mm gap from card edge */
        .cm {
            position: absolute;
            background: #666666;
        }

        /* top-left */
        .cm-tl-h {
            top: 121.5mm;
            left: 55.5mm;
            width: 5mm;
            height: 1pt;
        }

        .cm-tl-v {
            top: 115mm;
            left: 62mm;
            width: 1pt;
            height: 5mm;
        }

        /* top-right */
        .cm-tr-h {
            top: 121.5mm;
            left: 149.5mm;
            width: 5mm;
            height: 1pt;
        }

        .cm-tr-v {
            top: 115mm;
            left: 148mm;
            width: 1pt;
            height: 5mm;
        }

        /* bottom-left */
        .cm-bl-h {
            top: 175.5mm;
            left: 55.5mm;
            width: 5mm;
            height: 1pt;
        }

        .cm-bl-v {
            top: 176.5mm;
            left: 62mm;
            width: 1pt;
            height: 5mm;
        }

        /* bottom-right */
        .cm-br-h {
            top: 175.5mm;
            left: 149.5mm;
            width: 5mm;
            height: 1pt;
        }

        .cm-br-v {
            top: 176.5mm;
            left: 148mm;
            width: 1pt;
            height: 5mm;
        }

        /* Print-side label below card */
        .side-label {
            position: absolute;
            top: 180mm;
            left: 62mm;
            width: 86mm;
            text-align: center;
            font-size: 6pt;
            color: #888888;
            letter-spacing: 0.5pt;
        }

        /* ══════════════════════════════════════
           SHARED HEADER  (≈ 11 mm)
        ══════════════════════════════════════ */
        .c-hdr {
            background: #3849AB;
            border-radius: 2mm 2mm 0 0;
        }

        .c-hdr-tbl {
            width: 100%;
            border-collapse: collapse;
        }

        .c-hdr-logo-td {
            width: 12mm;
            text-align: center;
            vertical-align: middle;
            padding: 1mm 0mm 1mm 1.5mm;
        }

        .c-hdr-logo {
            width: 9mm;
            height: 15mm;
        }

        .c-hdr-title-td {
            vertical-align: middle;
            padding: 1mm 1mm 1mm 1.5mm;
        }

        .c-hdr-school {
            font-size: 8pt;
            font-weight: bold;
            color: #ffffff;
            line-height: 1.15;
        }

        .c-hdr-sub {
            font-size: 4.5pt;
            color: rgba(255, 255, 255, 0.75);
            margin-top: 0.5mm;
        }

        .c-hdr-right-td {
            width: 17mm;
            text-align: center;
            vertical-align: middle;
            padding-right: 1.5mm;
        }

        /* Badge (front only) */
        .c-badge {
            border: 0.7pt solid rgba(255, 255, 255, 0.55);
            border-radius: 1mm;
            padding: 0.8mm 1mm;
        }

        .c-badge-lo {
            font-size: 6pt;
            color: #ffffff;
            font-weight: bold;
            line-height: 1.2;
        }

        .c-badge-en {
            font-size: 4.5pt;
            color: rgba(255, 255, 255, 0.70);
        }

        /* Watermark logo (back only) */
        .c-logo-wm {
            width: 9mm;
            height: 9mm;
            opacity: 0.25;
        }

        /* Gold stripe */
        .c-gold {
            height: 2pt;
            background: #E8A020;
        }


        /* ══════════════════════════════════════
           FRONT — BODY
        ══════════════════════════════════════ */
        .fc-body {
            padding: 1mm 2mm 0;
        }

        .fc-body-tbl {
            width: 100%;
            border-collapse: collapse;
        }

        /* Photo column */
        .fc-photo-td {
            width: 19mm;
            vertical-align: top;
        }

        .fc-photo-frame {
            width: 15mm;
            height: 26mm;
            border: 1pt solid #E8A020;
            border-radius: 1mm;
            overflow: hidden;
            background: #f5f0e8;
        }

        .fc-photo-frame img {
            width: 15mm;
            height: 26mm;


        }

        .fc-photo-placeholder {
            width: 15mm;
            height: 26mm;
            text-align: center;
            padding-top: 6mm;
            font-size: 13pt;
        }

        /* Info column */
        .fc-info-td {
            vertical-align: top;
            padding-left: 1mm;
        }

        .fc-name {
            font-size: 7.5pt;
            font-weight: bold;
            color: #1a1a2e;
            line-height: 1.15;
            padding-bottom: 0.4mm;
            margin-bottom: 0.8mm;
            border-bottom: 1pt solid #E8A020;
        }

        .fc-fields-tbl {
            width: 100%;
            border-collapse: collapse;
        }

        .fc-lbl {
            font-size: 5pt;
            color: #999999;
            padding: 0 1mm 0.5mm 0;
            vertical-align: top;
            white-space: nowrap;
            width: 10mm;
        }

        .fc-val {
            font-size: 6.5pt;
            color: #111111;
            font-weight: bold;
            padding: 0 0 0.5mm 0;
            vertical-align: top;
            line-height: 1.15;
        }

        .fc-val-id {
            color: #3849AB;
        }


        /* ══════════════════════════════════════
           FRONT — FOOTER  (absolute at bottom)
        ══════════════════════════════════════ */
        .fc-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #f0f2fb;
            border-top: 0.5pt solid #d0d4ee;
            padding: 1mm 2mm;
        }

        .fc-ftr-tbl {
            width: 100%;
            border-collapse: collapse;
        }

        .fc-dates-td {
            vertical-align: middle;
            margin-left: 30mm;
        }

        .fc-date-lbl {
            font-size: 5pt;
            color: #666666;
        }

        .fc-date-val {
            font-size: 6pt;
            color: #111111;
            font-weight: bold;
        }

        .fc-date-row {
            margin-bottom: 0.5mm;
            margin-left: 5mm;
            line-height: 2mm;
        }

        .fc-qr-td {
            width: 13mm;
            text-align: right;
            vertical-align: middle;

        }

        .fc-qr-box {
            border: 0.7pt solid #3849AB;
            border-radius: 1mm;
            padding: 0.5mm;
            background: #ffffff;
            margin-right: 40px;
        }

        .fc-qr-box img {
            width: 9mm;
            height: 9mm;
            display: block;
        }

        .fc-qr-id {
            font-size: 3.5pt;
            color: #3849AB;
            text-align: center;
            margin-top: 0.2mm;
            font-weight: bold;
            margin-right: 40px;
        }


        /* ══════════════════════════════════════
           BACK — INFO GRID
        ══════════════════════════════════════ */
        .bc-info {
            padding: 0.8mm 2mm 0.2mm;
        }

        .bc-info-tbl {
            width: 100%;
            border-collapse: collapse;

        }

        .bc-cell {
            width: 50%;
            padding: 0 4mm 0.3mm 0;
            vertical-align: top;

        }

        .bc-cell.r {
            padding-left: 2mm;
            padding-right: 0;
            border-left: 0.5pt solid #eeeeee;
        }

        .bc-cell-lbl {
            font-size: 4pt;
            color: #aaaaaa;
            margin-bottom: 0.1mm;
        }

        .bc-cell-val {
            font-size: 6.5pt;
            color: #111111;
            font-weight: bold;
            line-height: 1.05;
        }

        /* Thin divider */
        .bc-div {
            height: 0.5pt;
            background: #dddddd;
            margin: 0 2mm;
        }

        /* ══════════════════════════════════════
           BACK — BOTTOM  (notes + signature)
        ══════════════════════════════════════ */
        .bc-bottom {
            padding: 0.3mm 2mm 0.5mm;
        }

        .bc-bot-tbl {
            width: 100%;
            border-collapse: collapse;
        }

        /* Notes */
        .bc-notes-td {
            vertical-align: top;
            padding-right: 1.5mm;
        }

        .bc-notes-box {
            border: 0.5pt solid #d0d4ee;
            border-radius: 1mm;
            padding: 1mm 1.5mm;
            background: #f8f9fd;
        }

        .bc-notes-title {
            font-size: 5.5pt;
            font-weight: bold;
            color: #3849AB;
            margin-bottom: 0.5mm;
        }

        .bc-notes-line {
            font-size: 4.5pt;
            color: #555555;
            margin-bottom: 0.3mm;
            line-height: 1.2;
        }

        /* Signature */
        .bc-sig-td {
            width: 40mm;
            text-align: center;
            vertical-align: bottom;
        }

        .bc-sig-lbl {
            font-size: 4.5pt;
            color: #444444;
            font-weight: bold;
            margin-bottom: 0.1mm;
        }

        .bc-stamp {
            width: 12mm;
            height: 9mm;
        }

        .bc-sig-line {
            height: 0.1pt;
            background: #333333;
            width: 20mm;
            margin: 0.1mm auto 0mm;
        }

        .bc-sig-name {
            font-size: 5pt;
            color: #111111;
            margin-top: -1mm;
        }

        .bc-sig-role {
            font-size: 4pt;
            color: #777777;
            margin-top: 0.2mm;
        }

        /* ══════════════════════════════════════
           BACK — FOOTER  (absolute at bottom)
        ══════════════════════════════════════ */
        .bc-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #3849AB;
            padding: 1mm 2mm;
            border-radius: 0 0 2mm 2mm;

        }

        .bc-ftr-tbl {
            width: 100%;
            border-collapse: collapse;

        }

        .bc-contact {
            font-size: 5.5pt;
            color: rgba(255, 255, 255, 0.88);
        }

        .bc-disclaimer {
            font-size: 4pt;
            color: rgba(255, 255, 255, 0.60);
            margin-top: 0.3mm;
        }
    </style>
</head>

<body>

    {{-- ══════════════════════════════════════════
    PAGE 1 — FRONT CARD
    ══════════════════════════════════════════ --}}
    <div class="page page-front">

        {{-- Crop / cut marks --}}
        <div class="cm cm-tl-h"></div>
        <div class="cm cm-tl-v"></div>
        <div class="cm cm-tr-h"></div>
        <div class="cm cm-tr-v"></div>
        <div class="cm cm-bl-h"></div>
        <div class="cm cm-bl-v"></div>
        <div class="cm cm-br-h"></div>
        <div class="cm cm-br-v"></div>

        <div class="side-label">FRONT · ດ້ານໜ້າ</div>

        <div class="card">

            {{-- Header --}}
            <div class="c-hdr">
                <table class="c-hdr-tbl">
                    <tr>
                        <td class="c-hdr-logo-td">
                            @if($logoBase64)
                                <img class="c-hdr-logo" src="{{ $logoBase64 }}" alt="logo" />
                            @endif
                        </td>
                        <td class="c-hdr-title-td">
                            <div class="c-hdr-school">{{ $schoolName }}</div>
                            <div class="c-hdr-sub">Ongtue Sangha Teacher Training College</div>
                        </td>
                        <td class="c-hdr-right-td">
                            <div class="c-badge">
                                <div class="c-badge-lo">ບັດນັກສຶກສາ</div>
                                <div class="c-badge-en">Student Card</div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="c-gold"></div>

            {{-- Body: photo + info --}}
            <div class="fc-body">
                <table class="fc-body-tbl">
                    <tr>
                        <td class="fc-photo-td">
                            <div class="fc-photo-frame">
                                @if($photoBase64)
                                    <img src="{{ $photoBase64 }}" alt="photo" />
                                @else
                                    @php $isMonastic = in_array($student->gender, ['ພຣະ', 'ສ.ນ']); @endphp
                                    <div class="fc-photo-placeholder"
                                        style="color:{{ $isMonastic ? '#9C7620' : '#5B6472' }};">
                                        {{ $isMonastic ? '☸' : '☻' }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="fc-info-td">
                            <div class="fc-name">{{ $student->gendered_name }}</div>
                            <table class="fc-fields-tbl">
                                <tr>
                                    <td class="fc-lbl">ສາຂາ</td>
                                    <td class="fc-val">{{ $student->major->name ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td class="fc-lbl">ສົກ/ຊັ້ນ</td>
                                    <td class="fc-val">{{ $student->academicYear->year ?? '—' }} · ປີ
                                        {{ $student->year_level ?? '—' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fc-lbl">ເພດ</td>
                                    <td class="fc-val">{{ $student->gender ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td class="fc-lbl">ລະຫັດ</td>
                                    <td class="fc-val fc-val-id">{{ $student->student_id ?? '—' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>

            {{-- Footer: dates + QR --}}
            <div class="fc-footer">
                <table class="fc-ftr-tbl">
                    <tr>
                        <td class="fc-dates-td">
                            <div class="fc-date-row">
                                <span class="fc-date-lbl">ວັນທີ່ອອກບັດ: </span>
                                <span class="fc-date-val">{{ $issueDate }}</span>
                            </div>
                            <div class="fc-date-row">
                                <span class="fc-date-lbl">ວັນທີ່ໝົດອາຍຸ: </span>
                                <span class="fc-date-val">{{ $expiryDate }}</span>
                            </div>
                        </td>
                        @if($qrBase64)
                            <td class="fc-qr-td">
                                <div class="fc-qr-box">
                                    <img src="{{ $qrBase64 }}" alt="QR" />
                                </div>
                                <div class="fc-qr-id">{{ $student->student_id }}</div>
                            </td>
                        @endif
                    </tr>
                </table>
            </div>

        </div>{{-- /card --}}
    </div>{{-- /page 1 --}}


    {{-- ══════════════════════════════════════════
    PAGE 2 — BACK CARD
    Same card position → perfect duplex alignment
    ══════════════════════════════════════════ --}}
    <div class="page">

        {{-- Identical crop marks — align on both sides --}}
        <div class="cm cm-tl-h"></div>
        <div class="cm cm-tl-v"></div>
        <div class="cm cm-tr-h"></div>
        <div class="cm cm-tr-v"></div>
        <div class="cm cm-bl-h"></div>
        <div class="cm cm-bl-v"></div>
        <div class="cm cm-br-h"></div>
        <div class="cm cm-br-v"></div>

        <div class="side-label">BACK · ດ້ານຫຼັງ</div>

        <div class="card">

            {{-- Header --}}
            <div class="c-hdr">
                <table class="c-hdr-tbl">
                    <tr>
                        <td class="c-hdr-logo-td">
                            @if($logoBase64)
                                <img class="c-hdr-logo" src="{{ $logoBase64 }}" alt="logo" />
                            @endif
                        </td>
                        <td class="c-hdr-title-td">
                            <div class="c-hdr-school">ກະຊວງສຶກສາທິການ ແລະ ກິລາ</div>
                            <div class="c-hdr-sub">ກົມພັດທະນາຄູ ແລະ ການບໍລິຫານການສຶກສາ</div>
                            <div class="c-hdr-sub">{{ $schoolName }}</div>
                        </td>

                    </tr>
                </table>
            </div>
            <div class="c-gold"></div>

            {{-- Info 2×2 grid --}}
            <div class="bc-info">
                <table class="bc-info-tbl">
                    <tr>
                        <td class="bc-cell">
                            <div class="bc-cell-lbl">ລະຫັດນັກສຶກສາ</div>
                            <div class="bc-cell-val">{{ $student->student_id ?? '—' }}</div>
                        </td>
                        <td class="bc-cell r">
                            <div class="bc-cell-lbl">ເບີໂທ</div>
                            <div class="bc-cell-val">{{ $student->phone ?? '—' }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="bc-cell">
                            <div class="bc-cell-lbl">ວັນ-ເດືອນ-ປີ ເກີດ</div>
                            <div class="bc-cell-val">{{ $dobFormatted ?? '—' }}</div>
                        </td>
                        <td class="bc-cell r">
                            <div class="bc-cell-lbl">ອີເມວ</div>
                            <div class="bc-cell-val">{{ $student->email ?? '—' }}</div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="bc-div"></div>

            {{-- Notes + Signature --}}
            <div class="bc-bottom">
                <table class="bc-bot-tbl">
                    <tr>
                        <td class="bc-notes-td">
                            <div class="bc-notes-box">
                                <div class="bc-notes-title">ເງື່ອນໄຂ</div>
                                <div class="bc-notes-line">- ໃຊ້ໄດ້ສະເພາະຜູ້ຖືທີ່ມີຊື່ ແລະ ຮູບໃນບັດ</div>
                                <div class="bc-notes-line">- ຫ້າມດັດແປງ ຫຼື ໂອນໃຫ້ຜູ້ອື່ນ</div>
                            </div>
                        </td>
                        <td class="bc-sig-td">
                            <div class="bc-sig-lbl">ຄະນະອຳນວຍການ</div>
                            @if($signatureBase64)
                                <img class="bc-stamp" src="{{ $signatureBase64 }}" alt="stamp" />
                            @else
                                <div style="height:10mm;"></div>
                            @endif

                            <div class="bc-sig-name">{{ $principalName }}</div>

                        </td>
                    </tr>
                </table>
            </div>

            {{-- Footer --}}
            <div class="bc-footer">
                <div class="bc-contact">ໂທ: {{ $schoolPhone }} | {{ $schoolWebsite }} | © {{ $schoolName }}</div>
            </div>

        </div>{{-- /card --}}
    </div>{{-- /page 2 --}}

</body>

</html>