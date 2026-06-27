<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ຂໍ້ມູນນັກສຶກສາ — {{ $student->gendered_name }}</title>
    <style>
        :root {
            --blue:   #3849AB;
            --gold:   #E8A020;
            --bg:     #f4f6fb;
            --card:   #ffffff;
            --text:   #1a1a2e;
            --muted:  #666;
            --border: #e0e4f0;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* ── Top nav ── */
        header {
            background: var(--blue);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-brand {
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
        }

        .nav-brand span {
            color: var(--gold);
        }

        .btn-logout {
            background: rgba(255,255,255,0.15);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.4);
            padding: 6px 16px;
            border-radius: 6px;
            font-size: 0.8rem;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-logout:hover { background: rgba(255,255,255,0.25); }

        /* ── Gold stripe ── */
        .stripe { height: 4px; background: var(--gold); }

        /* ── Layout ── */
        .container {
            max-width: 700px;
            margin: 24px auto;
            padding: 0 16px 40px;
        }

        /* ── Profile card ── */
        .profile-card {
            background: var(--card);
            border-radius: 12px;
            border: 1px solid var(--border);
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(56,73,171,.08);
        }

        .profile-header {
            background: var(--blue);
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .profile-photo {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: 3px solid var(--gold);
            object-fit: cover;
            background: #c9d0ee;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #fff;
            flex-shrink: 0;
            overflow: hidden;
        }

        .profile-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-name {
            color: #fff;
        }

        .profile-name h2 {
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .profile-name .badge {
            background: var(--gold);
            color: #fff;
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 600;
        }

        .profile-body {
            padding: 16px 20px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .info-item label {
            display: block;
            font-size: 0.7rem;
            color: var(--muted);
            margin-bottom: 2px;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .info-item .value {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text);
        }

        .info-item .value.id { color: var(--blue); }

        /* ── Section cards ── */
        .section-card {
            background: var(--card);
            border-radius: 12px;
            border: 1px solid var(--border);
            margin-bottom: 20px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(56,73,171,.06);
        }

        .section-title {
            padding: 12px 16px;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--blue);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ── GPA pill ── */
        .gpa-row {
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .gpa-label { font-size: 0.85rem; color: var(--muted); }

        .gpa-pill {
            background: var(--blue);
            color: #fff;
            font-size: 1.1rem;
            font-weight: 700;
            padding: 6px 20px;
            border-radius: 30px;
        }

        /* ── Grades table ── */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }

        thead th {
            background: #f0f2fb;
            color: var(--muted);
            font-weight: 600;
            font-size: 0.7rem;
            text-transform: uppercase;
            padding: 8px 12px;
            text-align: left;
            letter-spacing: .3px;
        }

        tbody td {
            padding: 8px 12px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        tbody tr:last-child td { border-bottom: none; }

        .grade-letter {
            font-weight: 700;
            font-size: 0.95rem;
        }

        .grade-A, .grade-B\+ { color: #27ae60; }
        .grade-B  { color: #2980b9; }
        .grade-C\+, .grade-C { color: #e67e22; }
        .grade-D, .grade-F { color: #e74c3c; }

        /* ── Invoice rows ── */
        .inv-row {
            padding: 10px 16px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.85rem;
        }

        .inv-row:last-child { border-bottom: none; }

        .inv-num { font-weight: 600; }
        .inv-date { color: var(--muted); font-size: 0.75rem; }

        .status-badge {
            font-size: 0.72rem;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 600;
        }

        .status-paid       { background: #d4edda; color: #155724; }
        .status-unpaid     { background: #fff3cd; color: #856404; }
        .status-cancelled  { background: #f8d7da; color: #721c24; }

        .inv-amount { font-weight: 700; color: var(--text); }

        /* ── Summary row ── */
        .summary-row {
            padding: 12px 16px;
            display: flex;
            gap: 12px;
            background: #f8f9fd;
        }

        .summary-item {
            flex: 1;
            text-align: center;
        }

        .summary-item .s-label { font-size: 0.68rem; color: var(--muted); margin-bottom: 2px; }
        .summary-item .s-val { font-size: 0.95rem; font-weight: 700; }
        .s-owed { color: #e74c3c; }
        .s-paid { color: #27ae60; }

        /* ── Empty state ── */
        .empty { text-align: center; padding: 24px; color: var(--muted); font-size: 0.85rem; }

        /* ── Footer ── */
        .footer {
            text-align: center;
            color: var(--muted);
            font-size: 0.72rem;
            margin-top: 24px;
        }
    </style>
</head>
<body>

<header>
    <div class="nav-brand">ວິທະຍາໄລຄູສົງ <span>ອົງຕື້</span></div>
    <form method="POST" action="{{ route('student.logout') }}">
        @csrf
        <button type="submit" class="btn-logout">ອອກຈາກລະບົບ</button>
    </form>
</header>
<div class="stripe"></div>

<div class="container">

    {{-- Profile --}}
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-photo">
                @if($student->photo && file_exists(storage_path('app/public/students/' . $student->photo)))
                    <img src="{{ asset('storage/students/' . $student->photo) }}" alt="photo" />
                @else
                    {{ in_array($student->gender, ['ພຣະ', 'ສ.ນ']) ? '☸' : '☻' }}
                @endif
            </div>
            <div class="profile-name">
                <h2>{{ $student->gendered_name }}</h2>
                <span class="badge">{{ $student->student_id }}</span>
            </div>
        </div>
        <div class="profile-body">
            <div class="info-grid">
                <div class="info-item">
                    <label>ລະຫັດ</label>
                    <div class="value id">{{ $student->student_id ?? '—' }}</div>
                </div>
                <div class="info-item">
                    <label>ສາຂາ</label>
                    <div class="value">{{ $student->major->name ?? '—' }}</div>
                </div>
                <div class="info-item">
                    <label>ສົກຮຽນ</label>
                    <div class="value">{{ $student->academicYear->year ?? '—' }}</div>
                </div>
                <div class="info-item">
                    <label>ປີຮຽນ</label>
                    <div class="value">ປີ {{ $student->year_level }}</div>
                </div>
                <div class="info-item">
                    <label>ເພດ</label>
                    <div class="value">{{ $student->gender ?? '—' }}</div>
                </div>
                <div class="info-item">
                    <label>ເບີໂທ</label>
                    <div class="value">{{ $student->phone ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Grades --}}
    <div class="section-card">
        <div class="section-title">
            📚 ຄະແນນ &amp; GPA
            @if($cumulativeGpa !== null)
                <span style="margin-left:auto">
                    <span class="gpa-pill">GPA {{ number_format($cumulativeGpa, 2) }}</span>
                </span>
            @endif
        </div>
        @if($grades->isEmpty())
            <div class="empty">ຍັງບໍ່ມີຄະແນນ</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>ວິຊາ</th>
                        <th>ສົກ / ພາກ</th>
                        <th style="text-align:center">ໜ່ວຍ</th>
                        <th style="text-align:center">ຄະແນນ</th>
                        <th style="text-align:center">ເກຣດ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grades as $g)
                    @php
                        $letter = match(true) {
                            $g->grade_point >= 3.50 => 'A',
                            $g->grade_point >= 3.00 => 'B+',
                            $g->grade_point >= 2.50 => 'B',
                            $g->grade_point >= 2.00 => 'C+',
                            $g->grade_point >= 1.50 => 'C',
                            $g->grade_point >= 1.00 => 'D',
                            default => 'F',
                        };
                        $colorClass = 'grade-' . str_replace('+', '\+', $letter);
                    @endphp
                    <tr>
                        <td>{{ $g->subject->subject_name ?? '—' }}</td>
                        <td style="color:var(--muted); font-size:.78rem">
                            {{ $g->academicYear->year ?? '—' }} / ພາກ {{ $g->semester }}
                        </td>
                        <td style="text-align:center">{{ $g->subject->credits ?? '—' }}</td>
                        <td style="text-align:center">{{ number_format($g->grade_point, 2) }}</td>
                        <td style="text-align:center">
                            <span class="grade-letter {{ $colorClass }}">{{ $letter }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Invoices --}}
    <div class="section-card">
        <div class="section-title">💳 ການຊຳລະຄ່າຮຽນ</div>

        @if($invoices->isNotEmpty())
        <div class="summary-row">
            <div class="summary-item">
                <div class="s-label">ຍັງຄ້າງ</div>
                <div class="s-val s-owed">{{ number_format($totalOwed) }} ₭</div>
            </div>
            <div class="summary-item">
                <div class="s-label">ຊຳລະແລ້ວ</div>
                <div class="s-val s-paid">{{ number_format($totalPaid) }} ₭</div>
            </div>
        </div>
        @endif

        @if($invoices->isEmpty())
            <div class="empty">ຍັງບໍ່ມີໃບແຈ້ງໜີ້</div>
        @else
            @foreach($invoices as $inv)
            <div class="inv-row">
                <div>
                    <div class="inv-num">{{ $inv->invoice_number }}</div>
                    <div class="inv-date">{{ $inv->created_at->format('d/m/Y') }}</div>
                </div>
                <div style="display:flex; align-items:center; gap:10px">
                    <span class="inv-amount">{{ number_format($inv->total_amount) }} ₭</span>
                    <span class="status-badge status-{{ $inv->payment_status }}">
                        @if($inv->payment_status === 'paid') ຊຳລະແລ້ວ
                        @elseif($inv->payment_status === 'unpaid') ຍັງຄ້າງ
                        @else ຍົກເລີກ
                        @endif
                    </span>
                </div>
            </div>
            @endforeach
        @endif
    </div>

    <div class="footer">ວິທະຍາໄລຄູສົງ ອົງຕື້ &nbsp;·&nbsp; ຂໍ້ມູນນີ້ຮັກສາໄວ້ເປັນຄວາມລັບ</div>
</div>

</body>
</html>
