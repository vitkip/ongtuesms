@extends('layouts.app')

@section('title', 'ແຜງຄວບຄຸມ')

@push('styles')
<style>
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes growBar {
        from { height: 0 !important; }
        to   { height: var(--bar-h); }
    }
    .stat-card {
        animation: slideUp 0.4s ease both;
    }
    .stat-card:nth-child(1) { animation-delay: 0.05s; }
    .stat-card:nth-child(2) { animation-delay: 0.12s; }
    .stat-card:nth-child(3) { animation-delay: 0.19s; }
    .stat-card:nth-child(4) { animation-delay: 0.26s; }

    .bar-col {
        animation: growBar 0.55s cubic-bezier(.22,1,.36,1) both;
        animation-delay: calc(var(--bi, 0) * 0.07s + 0.35s);
    }

    .chart-grid-line {
        border-top: 1px dashed rgba(195, 198, 213, 0.35);
    }

    @media (prefers-reduced-motion: reduce) {
        .stat-card, .bar-col { animation: none; }
    }
</style>
@endpush

@section('content')
    <!-- Welcome -->
    <div class="mb-8 flex justify-between items-end">
        <div>
            <p class="text-[10px] uppercase tracking-[0.18em] font-bold text-secondary mb-2">ພາບລວມລະບົບ</p>
            <h2 class="text-[1.75rem] font-bold text-on-background leading-tight">
                ສະບາຍດີ, <span class="text-primary">{{ Auth::user()->full_name ?? 'ຜູ້ບໍລິຫານ' }}</span>
            </h2>
            <p class="text-on-surface-variant text-sm mt-1">ການດຳເນີນງານຂອງວິທະຍາໄລຄູສົງ ອົງຕື້</p>
        </div>
        <div class="bg-primary px-5 py-3 rounded-xl flex items-center gap-3 shadow-sm">
            <span class="material-symbols-outlined text-secondary-container" style="font-variation-settings: 'FILL' 1; font-size: 20px;">calendar_today</span>
            <div>
                <p class="text-[9px] uppercase font-bold text-on-primary/60 leading-none tracking-widest">ສົກຮຽນປັດຈຸບັນ</p>
                <p class="text-sm font-bold text-on-primary mt-1">{{ $currentYearStr }}</p>
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-7">
        <!-- Students -->
        <div class="stat-card bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden flex flex-col hover-lift hover:shadow-md transition-shadow duration-300">
            <div class="h-[3px] w-full bg-primary"></div>
            <div class="p-6 flex flex-col flex-1">
                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center text-primary mb-5">
                    <span class="material-symbols-outlined" style="font-size: 20px;">group</span>
                </div>
                <p class="text-[10px] uppercase tracking-widest font-bold text-outline">ນັກສຶກສາທັງໝົດ</p>
                <h3 class="text-5xl font-bold text-primary mt-2 leading-none">{{ $studentsCount }}</h3>
                <p class="mt-4 text-xs text-green-600 font-semibold flex items-center gap-1">
                    <span class="material-symbols-outlined" style="font-size: 14px;">trending_up</span>
                    ຂໍ້ມູນຫຼ້າສຸດ
                </p>
            </div>
        </div>

        <!-- Subjects -->
        <div class="stat-card bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden flex flex-col hover-lift hover:shadow-md transition-shadow duration-300">
            <div class="h-[3px] w-full bg-secondary"></div>
            <div class="p-6 flex flex-col flex-1">
                <div class="w-10 h-10 bg-secondary/10 rounded-lg flex items-center justify-center text-secondary mb-5">
                    <span class="material-symbols-outlined" style="font-size: 20px;">library_books</span>
                </div>
                <p class="text-[10px] uppercase tracking-widest font-bold text-outline">ວິຊາຮຽນທັງໝົດ</p>
                <h3 class="text-5xl font-bold text-on-surface mt-2 leading-none">{{ $subjectsCount }}</h3>
                <p class="mt-4 text-xs text-outline font-semibold flex items-center gap-1">
                    <span class="material-symbols-outlined" style="font-size: 14px;">info</span>
                    ຕາມຫຼັກສູດປະຈຸບັນ
                </p>
            </div>
        </div>

        <!-- Majors -->
        <div class="stat-card bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden flex flex-col hover-lift hover:shadow-md transition-shadow duration-300">
            <div class="h-[3px] w-full" style="background: linear-gradient(90deg, #00327d 0%, #fed65b 100%);"></div>
            <div class="p-6 flex flex-col flex-1">
                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center text-primary mb-5">
                    <span class="material-symbols-outlined" style="font-size: 20px;">school</span>
                </div>
                <p class="text-[10px] uppercase tracking-widest font-bold text-outline">ສາຂາທີ່ເປີດສອນ</p>
                <h3 class="text-5xl font-bold text-on-surface mt-2 leading-none">{{ $majorsCount }}</h3>
                <div class="mt-4 flex flex-wrap gap-1.5">
                    @foreach($majorDistribution->take(3) as $major)
                        <span class="px-2 py-0.5 bg-primary/10 text-primary text-[9px] font-bold rounded-full border border-primary/20">{{ $major->code }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Academic Year (featured) -->
        <div class="stat-card bg-primary rounded-xl overflow-hidden flex flex-col shadow-md relative">
            <div class="h-[3px] w-full bg-secondary-container"></div>
            <div class="p-6 flex flex-col flex-1 relative">
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/5 rounded-full pointer-events-none"></div>
                <div class="absolute right-2 top-10 w-16 h-16 bg-white/5 rounded-full pointer-events-none"></div>
                <div class="w-10 h-10 bg-white/15 rounded-lg flex items-center justify-center mb-5">
                    <span class="material-symbols-outlined text-on-primary" style="font-size: 20px; font-variation-settings: 'FILL' 1;">event</span>
                </div>
                <p class="text-[10px] uppercase tracking-widest font-bold text-on-primary/60">ສົກຮຽນປັດຈຸບັນ</p>
                <h3 class="text-3xl font-bold text-on-primary mt-2 leading-tight">{{ $currentYearStr }}</h3>
                <p class="mt-4 text-xs text-secondary-container font-semibold flex items-center gap-1">
                    <span class="material-symbols-outlined" style="font-size: 14px;">schedule</span>
                    ເປີດໃຊ້ງານປົກກະຕິ
                </p>
            </div>
        </div>
    </div>

    <!-- Chart + Logs -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Bar Chart -->
        <div class="lg:col-span-2 bg-surface-container-lowest border border-outline-variant rounded-xl p-6 flex flex-col">
            <div class="mb-6">
                <h4 class="text-base font-bold text-on-surface">ການແຈກຢາຍນັກສຶກສາຕາມສາຂາ</h4>
                <p class="text-xs text-outline mt-0.5">ສະຖິຕິການລົງທະບຽນໃນສົກຮຽນນີ້</p>
            </div>

            @php
                $maxStudents = $majorDistribution->max('student_count') ?: 1;
                $maxBarPx = 160;
            @endphp

            <!-- Chart area -->
            <div class="relative" style="height: 200px;">
                <!-- Grid lines -->
                <div class="absolute inset-0 flex flex-col justify-between pointer-events-none pb-0">
                    @for($i = 0; $i < 5; $i++)
                        <div class="chart-grid-line"></div>
                    @endfor
                </div>
                <!-- Bars -->
                <div class="absolute inset-x-4 bottom-0 top-0 flex items-end justify-around gap-3 border-b-2 border-outline-variant/40">
                    @foreach($majorDistribution as $barIndex => $major)
                        @php $barPx = max((int) round(($major->student_count / $maxStudents) * $maxBarPx), 6); @endphp
                        <div class="flex-1 flex flex-col items-center gap-1" style="align-self: flex-end;">
                            <span class="text-primary font-bold text-[11px] whitespace-nowrap">{{ $major->student_count }}</span>
                            <div class="bar-col w-full rounded-t-md cursor-pointer hover:opacity-80 transition-opacity"
                                 style="--bar-h: {{ $barPx }}px; --bi: {{ $barIndex }}; height: {{ $barPx }}px; background: linear-gradient(to top, #00327d, #0047ab);"
                                 title="{{ $major->name }}: {{ $major->student_count }} ຄົນ"></div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- X-axis -->
            <div class="flex justify-around gap-3 px-4 mt-3">
                @foreach($majorDistribution as $major)
                    <div class="flex-1 flex flex-col items-center">
                        <p class="font-bold text-[11px] text-primary leading-none">{{ $major->code }}</p>
                        <p class="text-[9px] text-outline text-center mt-0.5 truncate w-full" title="{{ $major->name }}">{{ $major->name }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 pt-3 border-t border-outline-variant/30 flex items-center gap-2">
                <span class="w-3 h-2.5 rounded-sm" style="background: linear-gradient(to top, #00327d, #0047ab);"></span>
                <span class="text-xs text-outline font-semibold">ນັກສຶກສາທັງໝົດ</span>
            </div>
        </div>

        <!-- Activity Log -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
            <div class="px-5 py-4 border-b border-outline-variant flex items-center justify-between">
                <div>
                    <h4 class="text-base font-bold text-on-surface">ການເຄື່ອນໄຫວລ່າສຸດ</h4>
                    <p class="text-xs text-outline mt-0.5">ເຫດການທີ່ຜ່ານມາໃນລະບົບ</p>
                </div>
                <span class="material-symbols-outlined text-outline" style="font-size: 18px;">history</span>
            </div>
            <div class="flex-1 overflow-y-auto custom-scrollbar p-4 max-h-[340px]">
                <div class="space-y-0.5">
                    @forelse($recentLogs as $log)
                        @php
                            [$logBg, $logText, $logIcon] = match(true) {
                                in_array($log->level, ['error','critical']) => ['bg-error-container', 'text-on-error-container', 'error'],
                                $log->level === 'warning'                   => ['bg-amber-100', 'text-amber-700', 'warning'],
                                $log->level === 'success'                   => ['bg-green-100', 'text-green-700', 'check_circle'],
                                default                                      => ['bg-surface-container-high', 'text-primary', 'info'],
                            };
                        @endphp
                        <div class="flex gap-3 py-2.5 border-b border-outline-variant/20 last:border-0">
                            <div class="flex-shrink-0 w-7 h-7 rounded-full {{ $logBg }} {{ $logText }} flex items-center justify-center mt-0.5">
                                <span class="material-symbols-outlined" style="font-size: 14px; font-variation-settings: 'FILL' 1;">{{ $logIcon }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-on-surface leading-snug">{{ $log->message }}</p>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <p class="text-[10px] text-outline">{{ $log->created_at->diffForHumans() }}</p>
                                    <span class="text-outline-variant text-[10px]">·</span>
                                    <p class="text-[10px] text-outline truncate">{{ $log->user->full_name ?? 'ລະບົບ' }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <span class="material-symbols-outlined text-outline-variant" style="font-size: 36px;">inbox</span>
                            <p class="text-xs text-outline mt-2">ບໍ່ມີເຫດການໃໝ່</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Major Detail Table -->
    <div class="mt-5 bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-outline-variant flex items-center justify-between">
            <h4 class="text-base font-bold text-on-surface">ລາຍລະອຽດສາຂາວິຊາ</h4>
            <span class="text-[10px] uppercase tracking-widest font-bold text-outline bg-surface-container px-2.5 py-1 rounded-md">{{ $majorsList->count() }} ສາຂາ</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low">
                        <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-outline">ລະຫັດ</th>
                        <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-outline">ຊື່ສາຂາວິຊາ</th>
                        <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-outline text-center">ນັກສຶກສາ</th>
                        <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-outline text-center">ສະຖານະ</th>
                    </tr>
                </thead>
                <tbody>
                    @php $tableMax = $majorsList->max('student_count') ?: 1; @endphp
                    @forelse($majorsList as $major)
                        <tr class="border-t border-outline-variant/30 hover:bg-surface-container-low/60 transition-colors">
                            <td class="px-6 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-1 bg-primary/10 text-primary text-xs font-bold rounded-md">{{ $major->code }}</span>
                            </td>
                            <td class="px-6 py-3.5 text-sm text-on-surface">{{ $major->name }}</td>
                            <td class="px-6 py-3.5">
                                <div class="flex flex-col items-center gap-1.5">
                                    <span class="text-sm font-bold text-primary">{{ $major->student_count }} ຄົນ</span>
                                    <div class="w-24 h-1.5 bg-surface-container-high rounded-full overflow-hidden">
                                        <div class="h-full bg-primary rounded-full"
                                             style="width: {{ $tableMax > 0 ? round(($major->student_count / $tableMax) * 100) : 0 }}%;"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-center">
                                @if($major->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-green-50 text-green-700 text-[10px] font-bold rounded-full border border-green-200">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                        ເປີດໃຊ້ງານ
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-surface-container text-outline text-[10px] font-bold rounded-full border border-outline-variant">
                                        <span class="w-1.5 h-1.5 bg-outline rounded-full"></span>
                                        ປິດໃຊ້ງານ
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-outline text-xs">ບໍ່ມີຂໍ້ມູນສາຂາວິຊາ.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
