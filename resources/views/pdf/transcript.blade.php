<!DOCTYPE html>
<html lang="lo">

<head>
    <meta charset="UTF-8">
    <title>ໃບປະເມີນຜົນການຮຽນ - {{ $student->student_id ?: $student->id }}</title>
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Phetsarath', sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 15px 25px;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Phetsarath', sans-serif;
            margin: 0;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 5px;
            position: relative;
        }

        .header-top {
            font-size: 13px;
            font-weight: bold;
        }

        .header-motto {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header-logo {
            width: 55px;
            height: auto;
            margin: 3px auto;
            display: block;
        }

        /* Ministry + School info */
        .ministry-info {
            text-align: left;
            font-size: 9px;
            line-height: 1.3;
            margin-bottom: 0;
        }

        .doc-number {
            text-align: right;
            font-size: 9px;
            margin-top: -30px;
            margin-bottom: 5px;
        }

        .doc-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin: 8px 0 10px 0;
        }

        /* Student Info */
        .student-info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 10px;
        }

        .student-info-table td {
            padding: 1px 0;
            vertical-align: top;
        }

        .info-label {
            font-weight: bold;
        }

        /* Semester Block */
        .semester-block {
            margin-bottom: 3px;
            page-break-inside: avoid;
        }

        .semester-header {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            border: 1px solid #000;
            padding: 3px;
            background-color: #fff;
        }

        /* Grades Table - Two Column Layout */
        .grades-table {
            width: 100%;
            border-collapse: collapse;
        }

        .grades-table th,
        .grades-table td {
            border: 1px solid #000;
            padding: 2px 4px;
            font-size: 9px;
            vertical-align: middle;
        }

        .grades-table th {
            font-weight: bold;
            text-align: center;
            background-color: #fff;
            font-size: 8.5px;
        }

        .grades-table .col-no {
            width: 5%;
            text-align: center;
        }

        .grades-table .col-name {
            width: 22%;
            text-align: left;
        }

        .grades-table .col-credit {
            width: 8%;
            text-align: center;
        }

        .grades-table .col-gpa {
            width: 7%;
            text-align: center;
        }

        .grades-table .col-grade {
            width: 7%;
            text-align: center;
        }

        .summary-row td {
            font-weight: bold;
            text-align: center;
        }

        /* Bottom Summary */
        .summary-box {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .summary-box td {
            border: 1px solid #000;
            padding: 4px 8px;
            font-size: 11px;
            font-weight: bold;
        }

        .summary-box .label-cell {
            text-align: center;
            width: 50%;
        }

        .summary-box .value-cell {
            text-align: center;
            width: 50%;
        }

        /* Signature */
        .signature-section {
            margin-top: 15px;
            width: 100%;
            page-break-inside: avoid;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-cell {
            width: 50%;
            vertical-align: top;
            text-align: center;
            padding-top: 5px;
        }

        .signature-title {
            font-weight: bold;
            font-size: 11px;
        }

        .signature-date {
            text-align: right;
            font-size: 10px;
            margin-bottom: 5px;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <div class="header">
        @if($logoBase64)
            <img src="{{ $logoBase64 }}" class="header-logo" alt="" />
        @endif
        <div class="header-motto">ສາທາລະນະລັດ ປະຊາທິປະໄຕ ປະຊາຊົນລາວ</div>
        <div class="header-motto">ສັນຕິພາບ ເອກະລາດ ປະຊາທິປະໄຕ ເອກະພາບ ອັດທະນາຖາວອນ</div>

    </div>

    <!-- Ministry / School info + Document Number -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 3px;">
        <tr>
            <td style="width: 60%; vertical-align: top;">
                <div class="ministry-info">
                    ກະຊວງສຶກສາທິການ ແລະ ກິລາ<br>
                    ກົມພັດທະນາຄູ ແລະ ການບໍລິຫານການສຶກສາ<br>
                    {{ $schoolName }}
                </div>
            </td>
            <td style="width: 40%; vertical-align: top; text-align: right; font-size: 9px;">
                ເລກທີ {{ $documentNumber }}
            </td>
        </tr>
    </table>

    <!-- Document Title -->
    <div class="doc-title">ໃບປະເມີນຜົນການຮຽນ</div>

    <!-- Student Information -->
    <table class="student-info-table">
        <tr>
            <td style="width: 40%;">
                <span class="info-label">ຊື່ ແລະ ນາມສະກຸນ:</span> {{ $student->gendered_name }}
            </td>
            <td style="width: 25%;">&nbsp;</td>
            <td style="width: 35%; text-align: right;">
                <span class="info-label">ສຳລັກການສຶກສາ:</span> ປະລິນຍາຕີ
            </td>
        </tr>
        <tr>
            <td>
                <span class="info-label">ວັນ,ເດືອນ,ປີເກີດ:</span>
                @if($student->dob)
                    {{ $student->dob->day }}
                    {{ ['ມັງກອນ', 'ກຸມພາ', 'ມີນາ', 'ເມສາ', 'ພຶດສະພາ', 'ມິຖຸນາ', 'ກໍລະກົດ', 'ສິງຫາ', 'ກັນຍາ', 'ຕຸລາ', 'ພະຈິກ', 'ທັນວາ'][$student->dob->month - 1] }}
                    {{ $student->dob->year }}
                @else
                    -
                @endif
            </td>
            <td>&nbsp;</td>
            <td style="text-align: right;">
                <span class="info-label">ສາຍວິຊາ:</span> {{ $student->major->name ?? '-' }}
            </td>
        </tr>
        <tr>
            <td>
                <span class="info-label">ແຂວງເກີດ:</span> {{ $student->province ?? '-' }}
            </td>
            <td style="text-align: center;">
                <span class="info-label">ລະຫັດນັກສຶກສາ:</span> {{ $student->student_id ?? '-' }}
            </td>
            <td style="text-align: right;">
                <span class="info-label">ໄລຍະປີຮຽນ:</span> {{ $student->academicYear->year ?? '-' }}
            </td>
        </tr>
    </table>

    <!-- Semester Blocks -->
    @forelse($semesterBlocks as $block)
        <div class="semester-block">
            <!-- Semester Header -->
            <table class="grades-table">
                <tr>
                    <td colspan="10" class="semester-header" style="border: 1px solid #000;">
                        ປີການສຶກສາ {{ $block['year_name'] }} ພາກຮຽນທີ {{ $block['semester'] }}
                    </td>
                </tr>
                <!-- Column Headers (two-column layout) -->
                <tr>
                    <th class="col-no">ລ/ດ</th>
                    <th class="col-name">ຊື່ວິຊາ</th>
                    <th class="col-credit">ໜ່ວຍກິດ</th>
                    <th class="col-gpa">ສະເລ່ຍ</th>
                    <th class="col-grade">ລະດັບ</th>
                    <th class="col-no">ລ/ດ</th>
                    <th class="col-name">ຊື່ວິຊາ</th>
                    <th class="col-credit">ໜ່ວຍກິດ</th>
                    <th class="col-gpa">ສະເລ່ຍ</th>
                    <th class="col-grade">ລະດັບ</th>
                </tr>

                @php
                    $grades = $block['grades'];
                    $total = count($grades);
                    $half = ceil($total / 2);
                    // Split into left (odd-indexed: 1,3,5...) and right (even-indexed: 2,4,6...)
                    $leftGrades = [];
                    $rightGrades = [];
                    foreach ($grades as $idx => $g) {
                        if ($idx % 2 === 0) {
                            $leftGrades[] = $g;
                        } else {
                            $rightGrades[] = $g;
                        }
                    }
                    $maxRows = max(count($leftGrades), count($rightGrades));
                @endphp

                @for($i = 0; $i < $maxRows; $i++)
                    <tr>
                        @if(isset($leftGrades[$i]))
                            @php $leftNum = ($i * 2) + 1; @endphp
                            <td class="col-no">{{ str_pad($leftNum, 2, '0', STR_PAD_LEFT) }}</td>
                            <td class="col-name">{{ $leftGrades[$i]->subject->subject_name }}</td>
                            <td class="col-credit">{{ $leftGrades[$i]->subject->credits }}</td>
                            <td class="col-gpa">{{ number_format($leftGrades[$i]->grade_point, 2) }}</td>
                            <td class="col-grade">{{ $leftGrades[$i]->letter_grade ?? '-' }}</td>
                        @else
                            <td class="col-no">&nbsp;</td>
                            <td class="col-name">&nbsp;</td>
                            <td class="col-credit">&nbsp;</td>
                            <td class="col-gpa">&nbsp;</td>
                            <td class="col-grade">&nbsp;</td>
                        @endif

                        @if(isset($rightGrades[$i]))
                            @php $rightNum = ($i * 2) + 2; @endphp
                            <td class="col-no">{{ str_pad($rightNum, 2, '0', STR_PAD_LEFT) }}</td>
                            <td class="col-name">{{ $rightGrades[$i]->subject->subject_name }}</td>
                            <td class="col-credit">{{ $rightGrades[$i]->subject->credits }}</td>
                            <td class="col-gpa">{{ number_format($rightGrades[$i]->grade_point, 2) }}</td>
                            <td class="col-grade">{{ $rightGrades[$i]->letter_grade ?? '-' }}</td>
                        @else
                            <td class="col-no">&nbsp;</td>
                            <td class="col-name">&nbsp;</td>
                            <td class="col-credit">&nbsp;</td>
                            <td class="col-gpa">&nbsp;</td>
                            <td class="col-grade">&nbsp;</td>
                        @endif
                    </tr>
                @endfor

                <!-- Semester Summary Row -->
                <tr class="summary-row">
                    <td colspan="2" style="text-align: center; font-weight: bold;">ລວມພາກຮຽນ</td>
                    <td style="text-align: center; font-weight: bold;">{{ $block['credits_attempted'] }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ number_format($block['gpa'], 2) }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $block['gpa_letter'] }}</td>
                    <td colspan="5">&nbsp;</td>
                </tr>
            </table>
        </div>
    @empty
        <div style="padding: 20px; border: 1px solid #000; text-align: center;">
            ບໍ່ມີຂໍ້ມູນຄະແນນທີ່ຖືກບັນທຶກ ສຳລັບການສະແດງໃນໃບຄະແນນ.
        </div>
    @endforelse

    <!-- Cumulative Summary Box -->
    @if(!empty($semesterBlocks))
        <table class="summary-box">
            <tr>
                <td class="label-cell">ລວມໜ່ວຍກິດທັງຮວມ</td>
                <td class="value-cell">{{ $totalCreditsAttempted }}</td>
            </tr>
            <tr>
                <td class="label-cell">ຄະແນນສະເລ່ຍສະສົມ</td>
                <td class="value-cell">{{ number_format($cumulativeGpa, 2) }}</td>
            </tr>
            <tr>
                <td class="label-cell">ລະດັບ</td>
                <td class="value-cell">{{ $cumulativeGpaLetter }}</td>
            </tr>
        </table>
    @endif

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-date">
            ທີ່{{ $schoolName }}, {{ $printDate }}
        </div>
        <table class="signature-table">
            <tr>
                <td class="signature-cell">
                    <div class="signature-title">ຄະນະອຳນວຍການ</div>
                    <div style="margin-top: 60px;">
                        <div>(...................................................)</div>
                    </div>
                </td>
                <td class="signature-cell">
                    <div class="signature-title">ຫົວໜ້າພະແນກວິຊາການ</div>
                    <div style="margin-top: 60px;">
                        <div>(...................................................)</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>