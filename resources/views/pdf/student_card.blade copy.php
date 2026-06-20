<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8"/>
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

        @page { size: A4 portrait; margin: 0; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Phetsarath', sans-serif; background-color: #f4f6f9; }

        .page-break { page-break-after: always; }

        /* ==========================================
           FRONT SIDE STYLE
           ========================================== */
        .front {
            position: relative;
            width: 210mm;
            height: 297mm;
            overflow: hidden;
            background-color: #f4f6f9;
        }

        /* Top Header Band */
        .f-header-band {
            position: absolute;
            top: 0; left: 0;
            width: 210mm; height: 82mm;
            background-color: #0b2545;
            z-index: 1;
        }
        .f-header-gold {
            position: absolute;
            top: 82mm; left: 0;
            width: 210mm; height: 3.5mm;
            background-color: #d4af37;
            z-index: 1;
        }
        .f-header-content {
            position: absolute;
            top: 10mm; left: 0;
            width: 210mm;
            text-align: center;
            z-index: 5;
        }
        .fh-logo {
            width: 17mm; height: 17mm;
            object-fit: contain;
            display: block;
            margin: 0 auto 2mm;
        }
        .fh-name {
            font-size: 18pt;
            font-weight: bold;
            color: #ffffff;
            line-height: 1.2;
        }
        .fh-en {
            font-size: 9pt;
            color: #d4af37;
            margin-top: 1.5mm;
            font-weight: bold;
            letter-spacing: 0.5pt;
        }
        .fh-badge {
            display: inline-block;
            margin-top: 3.5mm;
            border: 1.5pt solid #d4af37;
            background-color: rgba(212, 175, 55, 0.12);
            padding: 1.5mm 6mm;
            font-size: 9pt;
            font-weight: bold;
            color: #ffffff;
            border-radius: 4px;
        }

        /* Circular Photo Frame */
        .f-photo-outer {
            position: absolute;
            top: 52mm; left: 65mm;
            width: 80mm; height: 80mm;
            border-radius: 50%;
            background-color: #d4af37;
            z-index: 10;
        }
        .f-photo-inner {
            position: absolute;
            top: 3.5mm; left: 3.5mm;
            width: 73mm; height: 73mm;
            border-radius: 50%;
            background-color: #ffffff;
            overflow: hidden;
        }
        .f-photo-inner img {
            width: 73mm; height: 73mm;
            border-radius: 50%;
            object-fit: cover;
            display: block;
        }
        .f-photo-inner table {
            width: 73mm; height: 73mm;
            border-radius: 50%;
        }
        .f-photo-inner table td {
            text-align: center;
            vertical-align: middle;
            font-size: 30pt;
        }

        /* Student Name & ID Badge */
        .f-name-section {
            position: absolute;
            top: 140mm; left: 0;
            width: 210mm;
            text-align: center;
            z-index: 5;
        }
        .f-student-name {
            font-size: 19pt;
            font-weight: bold;
            color: #0b2545;
            margin-bottom: 2mm;
        }
        .f-student-id-badge {
            display: inline-block;
            font-size: 11pt;
            font-weight: bold;
            color: #d4af37;
            background-color: #0b2545;
            padding: 1.2mm 5mm;
            border-radius: 3px;
            letter-spacing: 0.5pt;
        }

        /* Student Info Card */
        .f-info-container {
            position: absolute;
            top: 168mm; left: 15mm;
            width: 180mm;
            z-index: 5;
            border: 1.5pt solid #e5e9f0;
            border-radius: 8px;
            background-color: #ffffff;
            padding: 4mm;
        }
        .fi-tbl {
            width: 100%;
            border-collapse: collapse;
        }
        .fi-tbl tr.odd {
            background-color: #faf7f2;
        }
        .fi-tbl td {
            padding: 4mm 4mm;
            font-size: 11pt;
            vertical-align: middle;
        }
        .fi-lbl {
            width: 35%;
            color: #5a6b82;
            font-weight: bold;
        }
        .fi-sep {
            width: 5%;
            color: #d4af37;
            text-align: center;
            font-weight: bold;
        }
        .fi-val {
            font-weight: bold;
            color: #0b2545;
        }

        /* Property Note */
        .f-property-note {
            position: absolute;
            top: 264mm; left: 0;
            width: 210mm;
            text-align: center;
            font-size: 8.5pt;
            color: #5a6b82;
            font-style: italic;
            z-index: 5;
        }

        /* Bottom Accent */
        .f-footer-gold {
            position: absolute;
            top: 278.5mm; left: 0;
            width: 210mm; height: 3.5mm;
            background-color: #d4af37;
            z-index: 1;
        }
        .f-footer-band {
            position: absolute;
            top: 282mm; left: 0;
            width: 210mm; height: 15mm;
            background-color: #0b2545;
            z-index: 1;
        }


        /* ==========================================
           BACK SIDE STYLE
           ========================================== */
        .back {
            position: relative;
            width: 210mm;
            height: 297mm;
            overflow: hidden;
            background-color: #f4f6f9;
        }

        /* Top Header Band */
        .b-header-band {
            position: absolute;
            top: 0; left: 0;
            width: 210mm; height: 48mm;
            background-color: #0b2545;
            z-index: 1;
        }
        .b-header-gold {
            position: absolute;
            top: 48mm; left: 0;
            width: 210mm; height: 3.5mm;
            background-color: #d4af37;
            z-index: 1;
        }
        .b-header-content {
            position: absolute;
            top: 11mm; left: 0;
            width: 210mm;
            text-align: center;
            z-index: 5;
        }
        .bp-title {
            font-size: 18pt;
            font-weight: bold;
            color: #ffffff;
            line-height: 1.2;
        }
        .bp-sub {
            font-size: 9.5pt;
            color: #d4af37;
            margin-top: 2mm;
            font-weight: bold;
            letter-spacing: 0.5pt;
        }

        /* Terms & Rules */
        .b-terms-container {
            position: absolute;
            top: 64mm; left: 15mm;
            width: 180mm;
            z-index: 5;
        }
        .b-term-tbl {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4mm;
        }
        .b-term-tbl td {
            vertical-align: top;
            padding: 0;
        }
        .b-bullet-cell {
            width: 6mm;
            padding-top: 1.5mm;
        }
        .b-bullet-dot {
            width: 3mm; height: 3mm;
            border-radius: 50%;
            background-color: #d4af37;
        }
        .b-term-text {
            font-size: 9.5pt;
            color: #1d2a44;
            line-height: 1.6;
        }
        .b-term-bold {
            font-weight: bold;
            color: #0b2545;
        }

        /* Grid Contact & Validity */
        .b-grid-container {
            position: absolute;
            top: 124mm; left: 15mm;
            width: 180mm;
            z-index: 5;
        }
        .b-grid-tbl {
            width: 100%;
            border-collapse: collapse;
        }
        .b-grid-col {
            width: 48%;
            vertical-align: top;
            border: 1.5pt solid #e5e9f0;
            border-radius: 6px;
            background-color: #ffffff;
            padding: 4.5mm 5mm;
        }
        .b-grid-space {
            width: 4%;
        }
        .b-grid-title {
            font-size: 10pt;
            font-weight: bold;
            color: #0b2545;
            border-bottom: 1pt solid #e5e9f0;
            padding-bottom: 2mm;
            margin-bottom: 3mm;
        }
        
        /* Contact/Validity values */
        .bg-tbl {
            width: 100%;
            border-collapse: collapse;
        }
        .bg-tbl td {
            padding: 2mm 0;
            font-size: 9.5pt;
            vertical-align: middle;
        }
        .bg-lbl {
            width: 32%;
            color: #5a6b82;
            font-weight: bold;
        }
        .bg-sep {
            width: 8%;
            color: #d4af37;
            text-align: center;
        }
        .bg-val {
            font-weight: bold;
            color: #1d2a44;
        }

        /* Signature Stamp Area */
        .b-sig-container {
            position: absolute;
            top: 184mm; left: 0;
            width: 210mm;
            text-align: center;
            z-index: 5;
        }
        .b-sig-title {
            font-size: 10.5pt;
            color: #5a6b82;
            font-style: italic;
            margin-bottom: 12mm;
        }
        .b-sig-name {
            font-size: 12pt;
            font-weight: bold;
            color: #0b2545;
            border-top: 1.5pt solid #d4af37;
            display: inline-block;
            padding-top: 3mm;
            min-width: 80mm;
        }

        /* Footer Box Card (Logo + Name + QR) */
        .b-footer-card {
            position: absolute;
            top: 228mm; left: 15mm;
            width: 180mm;
            z-index: 5;
            border: 2pt solid #d4af37;
            border-radius: 8px;
            background-color: #ffffff;
            padding: 5mm 6mm;
        }
        .b-footer-tbl {
            width: 100%;
            border-collapse: collapse;
        }
        .bfl-logo {
            width: 14mm;
            vertical-align: middle;
            padding-right: 4mm;
        }
        .bfl-logo img {
            width: 14mm; height: 14mm;
            object-fit: contain;
        }
        .bfl-text {
            vertical-align: middle;
        }
        .bfl-school {
            font-size: 10.5pt;
            font-weight: bold;
            color: #0b2545;
        }
        .bfl-tagline {
            font-size: 8.5pt;
            color: #5a6b82;
            margin-top: 1mm;
        }
        .bfl-qr {
            text-align: right;
            vertical-align: middle;
            width: 24mm;
        }
        .bfl-qr img {
            width: 22mm; height: 22mm;
            display: inline-block;
        }

        /* Bottom Accent */
        .b-footer-gold {
            position: absolute;
            top: 278.5mm; left: 0;
            width: 210mm; height: 3.5mm;
            background-color: #d4af37;
            z-index: 1;
        }
        .b-footer-band {
            position: absolute;
            top: 282mm; left: 0;
            width: 210mm; height: 15mm;
            background-color: #0b2545;
            z-index: 1;
        }
    </style>
</head>
<body>

{{-- ==========================================
     PAGE 1: FRONT SIDE
     ========================================== --}}
<div class="front">

    {{-- Header Bands --}}
    <div class="f-header-band"></div>
    <div class="f-header-gold"></div>

    {{-- Header Content --}}
    <div class="f-header-content">
        @if($logoBase64)
            <img class="fh-logo" src="{{ $logoBase64 }}" alt="logo"/>
        @endif
        <span class="fh-name">{{ $schoolName }}</span><br/>
        <span class="fh-en">Buddhist Teachers College Ongtue</span><br/>
        <span class="fh-badge">ບັດນັກສຶກສາ &nbsp;|&nbsp; STUDENT CARD</span>
    </div>

    {{-- Circular Photo Frame --}}
    @php $isMonastic = in_array($student->gender, ['ພຣະ', 'ສ.ນ']); @endphp
    <div class="f-photo-outer">
        <div class="f-photo-inner">
            @if($photoBase64)
                <img src="{{ $photoBase64 }}" alt="{{ $student->full_name }}"/>
            @else
                <table style="background-color: {{ $isMonastic ? '#fdf6e2' : '#e6f0fa' }};">
                    <tr>
                        <td style="color: {{ $isMonastic ? '#b58900' : '#268bd2' }};">
                            {{ $isMonastic ? '☸' : '☻' }}
                        </td>
                    </tr>
                </table>
            @endif
        </div>
    </div>

    {{-- Student Name & ID --}}
    <div class="f-name-section">
        <div class="f-student-name">{{ $student->gendered_name }}</div>
        <div class="f-student-id-badge">{{ $student->student_id ?? '—' }}</div>
    </div>

    {{-- Student Info Panel --}}
    <div class="f-info-container">
        <table class="fi-tbl">
            <tr class="odd">
                <td class="fi-lbl">ສາຂາວິຊາ</td>
                <td class="fi-sep">:</td>
                <td class="fi-val">{{ $student->major->name ?? '—' }}</td>
            </tr>
            <tr>
                <td class="fi-lbl">ສົກສຶກສາ</td>
                <td class="fi-sep">:</td>
                <td class="fi-val">{{ $student->academicYear->year ?? '—' }}</td>
            </tr>
            <tr class="odd">
                <td class="fi-lbl">ຊັ້ນປີ / ເພດ</td>
                <td class="fi-sep">:</td>
                <td class="fi-val">ປີ {{ $student->year_level }} / {{ $student->gender }}</td>
            </tr>
            <tr>
                <td class="fi-lbl">ເບີໂທລະສັບ</td>
                <td class="fi-sep">:</td>
                <td class="fi-val">{{ $student->phone ?? '—' }}</td>
            </tr>
        </table>
    </div>

    {{-- Property note --}}
    <div class="f-property-note">ບັດນີ້ແມ່ນຊັບສິນຂອງ ວິທະຍາໄລຄູສົງ ອົງຕື້</div>

    {{-- Bottom Accents --}}
    <div class="f-footer-gold"></div>
    <div class="f-footer-band"></div>

</div>

<div class="page-break"></div>

{{-- ==========================================
     PAGE 2: BACK SIDE
     ========================================== --}}
<div class="back">

    {{-- Header Bands --}}
    <div class="b-header-band"></div>
    <div class="b-header-gold"></div>

    {{-- Header Content --}}
    <div class="b-header-content">
        <div class="bp-title">ຂໍ້ກຳນົດ ແລະ ເງື່ອນໄຂ</div>
        <div class="bp-sub">TERMS AND CONDITIONS</div>
    </div>

    {{-- Terms & Rules --}}
    <div class="b-terms-container">
        <table class="b-term-tbl">
            <tr>
                <td class="b-bullet-cell"><div class="b-bullet-dot"></div></td>
                <td class="b-term-text">
                    <span class="b-term-bold">ບັດນີ້ຕ້ອງຖືຕິດໂຕ​ທຸກ​ຄັ້ງ​ທີ່​ຢູ່​ໃນ​ວິ​ທະ​ຍາ​ໄລ,</span>
                    ຫາກ​ສູນ​ຫາຍ ຫຼື ເສຍ​ຫາຍ ຕ້ອງ​ລາຍ​ງານ​ໃຫ້​ສຳ​ນັກ​ງານ​ທະ​ບຽນ​ທັນ​ທີ.
                    ຫ້າມ​ນຳ​ໃຊ້​ໂດຍ​ຜູ້​ທີ່​ບໍ່​ແມ່ນ​ເຈົ້າ​ຂອງ​ໂດຍ​ເດັດ​ຂາດ.
                </td>
            </tr>
        </table>
        <table class="b-term-tbl">
            <tr>
                <td class="b-bullet-cell"><div class="b-bullet-dot"></div></td>
                <td class="b-term-text">
                    <span class="b-term-bold">ນັກ​ສຶກ​ສາ​ຕ້ອງ​ປະ​ຕິ​ບັດ​ຕາມ​ລະ​ບຽບ​ກົດ​ໝາຍ​ຂອງ​ວິ​ທະ​ຍາ​ໄລ,</span>
                    ຮັກ​ສາ​ຄວາມ​ສະ​ອາດ ຄວາມ​ເປັນ​ລະ​ບຽບ​ຮຽບ​ຮ້ອຍ ແລະ ນຸ່ງ​ຖືດ້ວຍ​ຊຸດ​ທີ່​ຖືກ​ຕ້ອງ​ຕາມ​ສະ​ຖານ​ະ.
                </td>
            </tr>
        </table>
    </div>

    {{-- Contact & Validity Grid --}}
    <div class="b-grid-container">
        <table class="b-grid-tbl">
            <tr>
                <!-- Column 1: Contact -->
                <td class="b-grid-col">
                    <div class="b-grid-title">ຂໍ້ມູນຕິດຕໍ່ / CONTACT</div>
                    <table class="bg-tbl">
                        <tr>
                            <td class="bg-lbl">Phone</td>
                            <td class="bg-sep">:</td>
                            <td class="bg-val">{{ $schoolPhone }}</td>
                        </tr>
                        <tr>
                            <td class="bg-lbl">Email</td>
                            <td class="bg-sep">:</td>
                            <td class="bg-val" style="font-size: 8.5pt;">{{ $student->email ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="bg-lbl">Website</td>
                            <td class="bg-sep">:</td>
                            <td class="bg-val" style="font-size: 8.5pt;">{{ $schoolWebsite }}</td>
                        </tr>
                    </table>
                </td>

                <!-- Spacer -->
                <td class="b-grid-space"></td>

                <!-- Column 2: Validity -->
                <td class="b-grid-col">
                    <div class="b-grid-title">ວັນທີອອກບັດ / VALIDITY</div>
                    <table class="bg-tbl">
                        <tr>
                            <td class="bg-lbl">JOIN</td>
                            <td class="bg-sep">:</td>
                            <td class="bg-val">{{ $issueDate }}</td>
                        </tr>
                        <tr>
                            <td class="bg-lbl">EXPIRED</td>
                            <td class="bg-sep">:</td>
                            <td class="bg-val">{{ $expiryDate }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    {{-- Signature Stamp Area --}}
    <div class="b-sig-container">
        <div class="b-sig-title">Signature & Stamp</div>
        <div class="b-sig-name">{{ $principalName }}</div>
    </div>

    {{-- Footer Card --}}
    <div class="b-footer-card">
        <table class="b-footer-tbl">
            <tr>
                @if($logoBase64)
                    <td class="bfl-logo">
                        <img src="{{ $logoBase64 }}" alt="logo"/>
                    </td>
                @endif
                <td class="bfl-text">
                    <div class="bfl-school">{{ $schoolName }}</div>
                    <div class="bfl-tagline">{{ $schoolWebsite }}</div>
                </td>
                @if($qrBase64)
                    <td class="bfl-qr">
                        <img src="{{ $qrBase64 }}" alt="QR"/>
                    </td>
                @endif
            </tr>
        </table>
    </div>

    {{-- Bottom Accents --}}
    <div class="b-footer-gold"></div>
    <div class="b-footer-band"></div>

</div>

</body>
</html>
