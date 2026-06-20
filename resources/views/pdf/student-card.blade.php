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
        body { font-family: 'Phetsarath', sans-serif; color: #1F3A5F; }

        .page-break { page-break-after: always; }

        /* ════════════════════════════════════════════════════
           PALETTE
           gold        #BF932E   gold-dark  #9C7620
           gold-tint   #F0E7CC   paper      #FBF8F1
           navy        #1F3A5F   slate      #5B6472
           hairline    #E5DCC4
        ════════════════════════════════════════════════════ */

        /* ══════════════════════════════════════
           FRONT — full A4 page card
        ══════════════════════════════════════ */
        .front {
            position: relative;
            width: 210mm;
            height: 297mm;
            overflow: hidden;
            background-color: #FBF8F1;
        }

        /* gold header band */
        .f-band {
            position: absolute;
            top: 0; left: 0;
            width: 210mm; height: 88mm;
            background-color: #BF932E;
            z-index: 1;
        }
        /* thin darker-gold rule under the band (clean divider) */
        .f-band-rule {
            position: absolute;
            top: 88mm; left: 0;
            width: 210mm; height: 2mm;
            background-color: #9C7620;
            z-index: 1;
        }

        /* header text on the band */
        .f-header {
            position: absolute;
            top: 9mm; left: 0;
            width: 210mm;
            text-align: center;
            z-index: 5;
        }
        .fh-logo  { width: 14mm; height: 14mm; object-fit: contain; display: block; margin: 0 auto 2.5mm; }
        .fh-name  { font-size: 16pt; font-weight: bold; color: #ffffff; line-height: 1.15; letter-spacing: 0.3pt; }
        .fh-en    { font-size: 8pt; color: rgba(255,255,255,0.92); margin-top: 2mm; letter-spacing: 1.2pt; text-transform: uppercase; }
        .fh-badge { display: inline-block; margin-top: 4mm; border: 0.8pt solid rgba(255,255,255,0.7); border-radius: 3mm; padding: 1.6mm 8mm; font-size: 8.5pt; font-weight: bold; color: #ffffff; letter-spacing: 0.5pt; }

        /* circular photo medallion — straddles the band edge */
        .f-ring {
            position: absolute;
            top: 47mm; left: 64mm;
            width: 82mm; height: 82mm;
            border-radius: 50%;
            background-color: #BF932E;     /* gold ring */
            z-index: 10;
        }
        .f-ring-gap {
            position: absolute;
            top: 4mm; left: 4mm;           /* 4mm gold ring */
            width: 74mm; height: 74mm;
            border-radius: 50%;
            background-color: #ffffff;
            overflow: hidden;
        }
        .f-ring-gap img {
            width: 74mm; height: 74mm;
            border-radius: 50%;
            object-fit: cover;
            display: block;
        }
        .f-ring-gap table { width: 74mm; height: 74mm; border-radius: 50%; }
        .f-ring-gap table td { text-align: center; vertical-align: middle; font-size: 30pt; }

        /* section eyebrow */
        .f-eyebrow {
            position: absolute;
            top: 140mm; left: 0;
            width: 210mm;
            text-align: center;
            z-index: 5;
        }
        .fe-tick { display: inline-block; width: 4mm; height: 4mm; background-color: #BF932E; border-radius: 1mm; vertical-align: middle; margin-right: 3mm; }
        .fe-lo   { font-size: 11pt; font-weight: bold; color: #1F3A5F; vertical-align: middle; }
        .fe-en   { font-size: 7.5pt; color: #9C7620; letter-spacing: 1.5pt; text-transform: uppercase; vertical-align: middle; margin-left: 3mm; }

        /* white info panel */
        .f-panel {
            position: absolute;
            top: 150mm; left: 24mm;
            width: 162mm;
            background-color: #ffffff;
            border: 0.5pt solid #E5DCC4;
            border-radius: 4mm;
            padding: 4mm 6mm;
            z-index: 5;
        }
        .fi-tbl { width: 100%; border-collapse: collapse; }
        .fi-tbl td {
            padding: 3.8mm 2mm;
            font-size: 10.5pt;
            vertical-align: middle;
            border-bottom: 0.4pt solid #EFEAD9;
        }
        .fi-tbl tr:last-child td { border-bottom: none; }
        .fi-zebra td { background-color: #FBF7EC; }
        .fi-lbl { width: 42%; color: #5B6472; font-size: 10pt; }
        .fi-sep { width: 4%; color: #C9BE9E; text-align: center; }
        .fi-val { font-weight: bold; color: #1F3A5F; }
        /* highlighted student-id row */
        .fi-idrow td { background-color: #F0E7CC; }
        .fi-idrow .fi-val { font-size: 12pt; color: #9C7620; letter-spacing: 0.6pt; }

        /* bottom framing bar */
        .f-footbar {
            position: absolute;
            bottom: 0; left: 0;
            width: 210mm; height: 11mm;
            background-color: #BF932E;
            text-align: center;
            z-index: 5;
        }
        .f-footbar span {
            display: inline-block;
            margin-top: 3.4mm;
            font-size: 8pt;
            color: #ffffff;
            letter-spacing: 0.6pt;
        }

        /* ══════════════════════════════════════
           BACK — full A4 page card
        ══════════════════════════════════════ */
        .back {
            position: relative;
            width: 210mm;
            height: 297mm;
            overflow: hidden;
            background-color: #FBF8F1;
        }

        /* gold pill header */
        .b-pill-wrap { padding: 12mm 14mm 0; }
        .b-pill {
            background-color: #BF932E;
            border-radius: 6mm;
            padding: 7mm 15mm;
            text-align: center;
        }
        .bp-title { font-size: 14pt; font-weight: bold; color: #ffffff; letter-spacing: 0.5pt; }
        .bp-sub   { font-size: 8pt; color: rgba(255,255,255,0.9); margin-top: 2mm; letter-spacing: 1.5pt; text-transform: uppercase; }

        .b-body { padding: 9mm 18mm 0; }

        /* generic eyebrow on the back */
        .b-eyebrow { margin: 0 0 4mm; }
        .be-tick { display: inline-block; width: 4mm; height: 4mm; background-color: #BF932E; border-radius: 1mm; vertical-align: middle; margin-right: 3mm; }
        .be-lo   { font-size: 10.5pt; font-weight: bold; color: #1F3A5F; vertical-align: middle; }
        .be-en   { font-size: 7.5pt; color: #9C7620; letter-spacing: 1.5pt; text-transform: uppercase; vertical-align: middle; margin-left: 3mm; }

        /* terms panel */
        .b-terms-panel {
            background-color: #ffffff;
            border: 0.5pt solid #E5DCC4;
            border-radius: 4mm;
            padding: 5mm 6mm 2mm;
            margin-bottom: 8mm;
        }
        .b-term-tbl { width: 100%; border-collapse: collapse; margin-bottom: 4mm; }
        .b-term-tbl td { vertical-align: top; padding: 0; }
        .b-dot-cell { width: 8mm; padding-top: 1mm; }
        .b-dot { width: 4mm; height: 4mm; border-radius: 1mm; background-color: #BF932E; }
        .b-term-text { font-size: 8.5pt; color: #5B6472; line-height: 1.65; }
        .b-term-bold { font-weight: bold; color: #1F3A5F; }

        /* contact rows */
        .b-contact { width: 100%; border-collapse: collapse; margin-bottom: 9mm; }
        .b-contact td { padding: 2.6mm 1mm; font-size: 9.5pt; border-bottom: 0.4pt solid #EFEAD9; }
        .b-contact tr:last-child td { border-bottom: none; }
        .bc-lbl { width: 24%; color: #5B6472; }
        .bc-sep { width: 5%; color: #C9BE9E; }
        .bc-val { font-weight: bold; color: #1F3A5F; }

        /* signature */
        .b-sig { text-align: center; padding: 2mm 0 9mm; }
        .b-sig-script { font-size: 12pt; color: #9C7620; font-style: italic; margin-bottom: 9mm; }
        .b-sig-name   { font-size: 11pt; font-weight: bold; color: #1F3A5F; border-top: 0.8pt solid #9C7620; display: inline-block; padding-top: 3mm; min-width: 72mm; }

        /* JOIN / EXPIRED date cards */
        .b-dates { width: 100%; border-collapse: collapse; margin-bottom: 10mm; }
        .b-dates .dc { width: 47%; vertical-align: top; }
        .b-dates .dc-gap { width: 6%; }
        .b-date-card {
            border: 0.6pt solid #E5DCC4;
            border-radius: 3mm;
            background-color: #ffffff;
            padding: 4mm 5mm;
            text-align: center;
        }
        .bd-lbl { font-size: 8pt; font-weight: bold; color: #9C7620; letter-spacing: 1.5pt; text-transform: uppercase; }
        .bd-val { font-size: 11pt; font-weight: bold; color: #1F3A5F; margin-top: 1.5mm; }

        /* footer box */
        .b-footer-box {
            border: 1.4pt solid #BF932E;
            border-radius: 3mm;
            padding: 5mm 7mm;
        }
        .b-footer-tbl { width: 100%; border-collapse: collapse; }
        .bfl-logo { width: 14mm; vertical-align: middle; padding-right: 4mm; }
        .bfl-logo img { width: 13mm; height: 13mm; object-fit: contain; }
        .bfl-text { vertical-align: middle; }
        .bfl-school  { font-size: 9.5pt; font-weight: bold; color: #1F3A5F; }
        .bfl-tagline { font-size: 7.5pt; color: #5B6472; margin-top: 1mm; letter-spacing: 0.3pt; }
        .bfl-qr { text-align: right; vertical-align: middle; width: 24mm; }
        .bfl-qr img { width: 22mm; height: 22mm; }

        /* back bottom framing bar */
        .b-footbar {
            position: absolute;
            bottom: 0; left: 0;
            width: 210mm; height: 11mm;
            background-color: #BF932E;
            text-align: center;
        }
        .b-footbar span { display: inline-block; margin-top: 3.4mm; font-size: 8pt; color: #ffffff; letter-spacing: 0.6pt; }
    </style>
</head>
<body>

{{-- ════════════════════════════════
     PAGE 1  ·  FRONT
════════════════════════════════ --}}
<div class="front">

    {{-- gold band + rule --}}
    <div class="f-band"></div>
    <div class="f-band-rule"></div>

    {{-- header text --}}
    <div class="f-header">
        @if($logoBase64)
            <img class="fh-logo" src="{{ $logoBase64 }}" alt="logo"/>
        @endif
        <span class="fh-name">{{ $schoolName }}</span><br/>
        <span class="fh-en">Buddhist Teachers College Ongtue</span><br/>
        <span class="fh-badge">ບັດນັກສຶກສາ &nbsp;|&nbsp; STUDENT CARD</span>
    </div>

    {{-- circular photo medallion --}}
    @php $isMonastic = in_array($student->gender, ['ພຣະ', 'ສ.ນ']); @endphp
    <div class="f-ring">
        <div class="f-ring-gap">
            @if($photoBase64)
                <img src="{{ $photoBase64 }}" alt="{{ $student->full_name }}"/>
            @else
                <table style="background-color:{{ $isMonastic ? '#F2E8CC' : '#E7E1D2' }};">
                    <tr>
                        <td style="color:{{ $isMonastic ? '#9C7620' : '#5B6472' }};">
                            {{ $isMonastic ? '☸' : '☻' }}
                        </td>
                    </tr>
                </table>
            @endif
        </div>
    </div>

    {{-- section eyebrow --}}
    <div class="f-eyebrow">
        <span class="fe-tick"></span><span class="fe-lo">ຂໍ້ມູນນັກສຶກສາ</span><span class="fe-en">Student Information</span>
    </div>

    {{-- white info panel --}}
    <div class="f-panel">
        <table class="fi-tbl">
            <tr class="fi-idrow">
                <td class="fi-lbl">ລະຫັດນັກສຶກສາ</td>
                <td class="fi-sep">:</td>
                <td class="fi-val">{{ $student->student_id ?? '—' }}</td>
            </tr>
            <tr>
                <td class="fi-lbl">ຊື່ ແລະ ນາມສະກຸນ</td>
                <td class="fi-sep">:</td>
                <td class="fi-val">{{ $student->gendered_name }}</td>
            </tr>
            <tr class="fi-zebra">
                <td class="fi-lbl">ສາຂາວິຊາ</td>
                <td class="fi-sep">:</td>
                <td class="fi-val">{{ $student->major->name ?? '—' }}</td>
            </tr>
            <tr>
                <td class="fi-lbl">ສົກສຶກສາ</td>
                <td class="fi-sep">:</td>
                <td class="fi-val">{{ $student->academicYear->year ?? '—' }}</td>
            </tr>
            <tr class="fi-zebra">
                <td class="fi-lbl">ຊັ້ນປີ / ສະຖານະ</td>
                <td class="fi-sep">:</td>
                <td class="fi-val">ປີ {{ $student->year_level }} / {{ $student->gender }}</td>
            </tr>
            <tr>
                <td class="fi-lbl">ເບີໂທ</td>
                <td class="fi-sep">:</td>
                <td class="fi-val">{{ $student->phone ?? '—' }}</td>
            </tr>
        </table>
    </div>

    {{-- bottom framing bar --}}
    <div class="f-footbar">
        <span>{{ $schoolWebsite }} &nbsp;·&nbsp; ບັດປະຈຳຕົວນັກສຶກສາ</span>
    </div>

</div>{{-- /front --}}
<div class="page-break"></div>

{{-- ════════════════════════════════
     PAGE 2  ·  BACK
════════════════════════════════ --}}
<div class="back">

    {{-- gold pill header --}}
    <div class="b-pill-wrap">
        <div class="b-pill">
            <div class="bp-title">ຂໍ້ກຳນົດ ແລະ ເງື່ອນໄຂ</div>
            <div class="bp-sub">Terms and Conditions</div>
        </div>
    </div>

    <div class="b-body">

        {{-- terms panel --}}
        <div class="b-terms-panel">
            <table class="b-term-tbl">
                <tr>
                    <td class="b-dot-cell"><div class="b-dot"></div></td>
                    <td class="b-term-text">
                        <span class="b-term-bold">ບັດນີ້ຕ້ອງຖືຕິດໂຕ​ທຸກ​ຄັ້ງ​ທີ່​ຢູ່​ໃນ​ວິ​ທະ​ຍາ​ໄລ,</span>
                        ຫາກ​ສູນ​ຫາຍ ຫຼື ເສຍ​ຫາຍ ຕ້ອງ​ລາຍ​ງານ​ໃຫ້​ສຳ​ນັກ​ງານ​ທະ​ບຽນ​ທັນ​ທີ.
                        ຫ້າມ​ນຳ​ໃຊ້​ໂດຍ​ຜູ້​ທີ່​ບໍ່​ແມ່ນ​ເຈົ້າ​ຂອງ​ໂດຍ​ເດັດ​ຂາດ.
                    </td>
                </tr>
            </table>
            <table class="b-term-tbl">
                <tr>
                    <td class="b-dot-cell"><div class="b-dot"></div></td>
                    <td class="b-term-text">
                        <span class="b-term-bold">ນັກ​ສຶກ​ສາ​ຕ້ອງ​ປະ​ຕິ​ບັດ​ຕາມ​ລະ​ບຽບ​ກົດ​ໝາຍ​ຂອງ​ວິ​ທະ​ຍາ​ໄລ,</span>
                        ຮັກ​ສາ​ຄວາມ​ສະ​ອາດ ຄວາມ​ເປັນ​ລະ​ບຽບ​ຮຽບ​ຮ້ອຍ ແລະ ນຸ່ງ​ຖືດ້ວຍ​ຊຸດ​ທີ່​ຖືກ​ຕ້ອງ​ຕາມ​ສະ​ຖານ​ະ.
                    </td>
                </tr>
            </table>
        </div>

        {{-- contact info --}}
        <div class="b-eyebrow">
            <span class="be-tick"></span><span class="be-lo">ຂໍ້ມູນຕິດຕໍ່</span><span class="be-en">Contact</span>
        </div>
        <table class="b-contact">
            <tr>
                <td class="bc-lbl">Phone</td>
                <td class="bc-sep">:</td>
                <td class="bc-val">{{ $schoolPhone }}</td>
            </tr>
            <tr>
                <td class="bc-lbl">Email</td>
                <td class="bc-sep">:</td>
                <td class="bc-val">{{ $student->email ?? '—' }}</td>
            </tr>
            <tr>
                <td class="bc-lbl">Website</td>
                <td class="bc-sep">:</td>
                <td class="bc-val">{{ $schoolWebsite }}</td>
            </tr>
        </table>

        {{-- signature --}}
        <div class="b-sig">
            <div class="b-sig-script">ລາຍເຊັນ / Signature</div>
            <div class="b-sig-name">{{ $principalName }}</div>
        </div>

        {{-- JOIN / EXPIRED date cards --}}
        <table class="b-dates">
            <tr>
                <td class="dc">
                    <div class="b-date-card">
                        <div class="bd-lbl">JOIN</div>
                        <div class="bd-val">{{ $issueDate }}</div>
                    </div>
                </td>
                <td class="dc-gap"></td>
                <td class="dc">
                    <div class="b-date-card">
                        <div class="bd-lbl">EXPIRED</div>
                        <div class="bd-val">{{ $expiryDate }}</div>
                    </div>
                </td>
            </tr>
        </table>

        {{-- footer box: logo + school + QR --}}
        <div class="b-footer-box">
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

    </div>{{-- /b-body --}}

    {{-- bottom framing bar --}}
    <div class="b-footbar">
        <span>{{ $schoolName }} &nbsp;·&nbsp; {{ $schoolWebsite }}</span>
    </div>

</div>{{-- /back --}}

</body>
</html>
