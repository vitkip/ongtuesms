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

    .bar-col {
        animation: growBar 0.55s cubic-bezier(.22,1,.36,1) both;
        animation-delay: calc(var(--bi, 0) * 0.07s + 0.3s);
    }

    .chart-grid-line {
        border-top: 1px dashed rgba(195, 198, 213, 0.35);
    }

    @media (prefers-reduced-motion: reduce) {
        .stat-card, .bar-col { animation: none; }
    }
</style>
@endpush

<div>
    <!-- Header Banner -->
    <div class="relative overflow-hidden rounded-2xl text-on-primary p-8 mb-8 shadow-lg"
         style="background: linear-gradient(135deg, #00327d 0%, #0047ab 55%, #002f6c 100%);">
        <!-- Subtle grid pattern -->
        <div class="absolute inset-0 opacity-[0.04]"
             style="background-image: repeating-linear-gradient(0deg, #fff 0, #fff 1px, transparent 0, transparent 32px), repeating-linear-gradient(90deg, #fff 0, #fff 1px, transparent 0, transparent 32px);"></div>
        <!-- Kip watermark -->
        <div class="absolute right-6 top-1/2 -translate-y-1/2 opacity-[0.06] select-none pointer-events-none hidden md:block">
            <span class="font-black text-white leading-none" style="font-size: 130px;">₭</span>
        </div>
        <div class="relative z-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-secondary-container text-base">payments</span>
                    <span class="text-[10px] font-bold text-white/50 tracking-[0.2em] uppercase">Finance &amp; Billing</span>
                </div>
                <h2 class="text-3xl font-bold text-white leading-tight">ລະບົບການເງິນ ແລະ ໃບບິນ</h2>
                <p class="text-sm text-white/65 mt-1.5 max-w-md">ຄຸ້ມຄອງໃບບິນ, ຕິດຕາມສະຖານະ ແລະ ອອກ PDF ພ້ອມ QR ໂອນເງິນ</p>
            </div>
            <div class="flex flex-wrap gap-3 w-full lg:w-auto">
                <a href="{{ route('invoices.report', ['search' => $searchInvoice, 'status' => $filterStatus]) }}"
                   target="_blank"
                   class="flex-1 lg:flex-none text-center bg-white/10 hover:bg-white/20 text-white px-5 py-3 rounded-xl font-bold flex items-center justify-center gap-2 active:scale-[0.98] transition-all border border-white/20 shadow-md">
                    <span class="material-symbols-outlined text-lg">picture_as_pdf</span>
                    <span>ອອກລາຍງານ PDF</span>
                </a>
                <button wire:click="openCreateModal"
                        class="flex-1 lg:flex-none bg-secondary-container text-on-background px-5 py-3 rounded-xl font-bold flex items-center justify-center gap-2 hover:brightness-105 active:scale-[0.98] transition-all shadow-lg border border-white/10">
                    <span class="material-symbols-outlined text-lg">receipt_long</span>
                    <span>ສ້າງໃບບິນໃໝ່</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Stats & Chart Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Stats Sidebar (Left 1/3) -->
        <div class="space-y-4 flex flex-col justify-between">
            <!-- Paid Card -->
            <div class="stat-card bg-surface-container-lowest border border-outline-variant rounded-2xl overflow-hidden flex flex-col p-5 hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">payments</span>
                    </div>
                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-150">ຊຳລະແລ້ວ</span>
                </div>
                <p class="text-[10px] uppercase tracking-widest font-bold text-outline mt-4">ລາຍຮັບທັງໝົດ</p>
                <h3 class="text-2xl font-bold text-emerald-700 mt-1.5 leading-none">
                    {{ number_format($totalPaid) }} <span class="text-xs font-semibold text-outline">ກີບ</span>
                </h3>
            </div>

            <!-- Unpaid Card -->
            <div class="stat-card bg-surface-container-lowest border border-outline-variant rounded-2xl overflow-hidden flex flex-col p-5 hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">pending</span>
                    </div>
                    <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-150">ຄ້າງຊຳລະ</span>
                </div>
                <p class="text-[10px] uppercase tracking-widest font-bold text-outline mt-4">ຍອດຄ້າງຊຳລະ</p>
                <h3 class="text-2xl font-bold text-amber-700 mt-1.5 leading-none">
                    {{ number_format($totalUnpaid) }} <span class="text-xs font-semibold text-outline">ກີບ</span>
                </h3>
            </div>

            <!-- Cancelled Card -->
            <div class="stat-card bg-surface-container-lowest border border-outline-variant rounded-2xl overflow-hidden flex flex-col p-5 hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-red-600">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">cancel</span>
                    </div>
                    <span class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-full border border-red-150">ຍົກເລີກ</span>
                </div>
                <p class="text-[10px] uppercase tracking-widest font-bold text-outline mt-4">ຍອດຍົກເລີກ</p>
                <h3 class="text-2xl font-bold text-red-700 mt-1.5 leading-none">
                    {{ number_format($totalCancelled) }} <span class="text-xs font-semibold text-outline">ກີບ</span>
                </h3>
            </div>
        </div>

        <!-- Chart Card (Right 2/3) -->
        <div class="lg:col-span-2 bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 flex flex-col hover:shadow-md transition-all">
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h4 class="text-base font-bold text-on-surface">ກຣາຟລາຍຮັບປະຈຳເດືອນ</h4>
                    <p class="text-xs text-outline mt-0.5">ສະຖິຕິລາຍຮັບລວມ 6 ເດືອນຫຼ້າສຸດ</p>
                </div>
                <span class="material-symbols-outlined text-outline" style="font-size: 18px;">bar_chart</span>
            </div>

            @php
                $maxRevenue = collect($monthlyRevenue)->max('total') ?: 1;
                $maxBarHeight = 140; // max px height for bars
            @endphp

            <!-- Chart area -->
            <div class="relative flex-1" style="min-height: 180px;">
                <!-- Grid lines -->
                <div class="absolute inset-0 flex flex-col justify-between pointer-events-none pb-0">
                    @for($i = 0; $i < 5; $i++)
                        <div class="chart-grid-line"></div>
                    @endfor
                </div>
                <!-- Bars -->
                <div class="absolute inset-x-4 bottom-0 top-0 flex items-end justify-around gap-4 border-b-2 border-outline-variant/40">
                    @foreach($monthlyRevenue as $idx => $dataPoint)
                        @php 
                            $barHeight = max((int) round(($dataPoint['total'] / $maxRevenue) * $maxBarHeight), 6); 
                        @endphp
                        <div class="flex-1 flex flex-col items-center gap-1.5" style="align-self: flex-end;">
                            <span class="text-primary font-bold text-[10px] whitespace-nowrap" title="{{ number_format($dataPoint['total']) }} LAK">
                                {{ $dataPoint['total'] > 0 ? (round($dataPoint['total'] / 1000000, 1) . 'M') : '0' }}
                            </span>
                            <div class="bar-col w-full rounded-t-md cursor-pointer hover:opacity-85 transition-opacity"
                                 style="--bar-h: {{ $barHeight }}px; --bi: {{ $idx }}; height: {{ $barHeight }}px; background: linear-gradient(to top, #00327d, #0047ab);"
                                 title="{{ $dataPoint['month'] }}: {{ number_format($dataPoint['total']) }} ກີບ"></div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- X-axis labels -->
            <div class="flex justify-around gap-4 px-4 mt-3">
                @foreach($monthlyRevenue as $dataPoint)
                    <div class="flex-1 text-center">
                        <p class="font-bold text-[10px] text-primary leading-none">{{ $dataPoint['month'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg text-sm flex items-center gap-3 shadow-sm">
            <span class="material-symbols-outlined text-green-600 shrink-0">check_circle</span>
            <span class="font-semibold">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Filters Section -->
    <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant flex flex-wrap items-end gap-4 mb-6 shadow-sm">
        <!-- Search -->
        <div class="flex-1 min-w-[240px]">
            <label class="block text-[10px] font-bold text-primary/60 uppercase tracking-widest mb-1.5">ຄົ້ນຫາ</label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-outline text-[18px] pointer-events-none">search</span>
                <input wire:model.live.debounce.300ms="searchInvoice"
                       type="text"
                       class="w-full bg-background border border-outline-variant rounded-lg pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                       placeholder="ເລກໃບບິນ, ຊື່ ຫຼື ລະຫັດນັກສຶກສາ..."/>
            </div>
        </div>
        <!-- Status Filter -->
        <div class="w-52 min-w-[180px]">
            <label class="block text-[10px] font-bold text-primary/60 uppercase tracking-widest mb-1.5">ສະຖານະ</label>
            <select wire:model.live="filterStatus"
                    class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                <option value="">ທັງໝົດ</option>
                <option value="paid">ຊຳລະແລ້ວ</option>
                <option value="unpaid">ຍັງບໍ່ຊຳລະ</option>
                <option value="cancelled">ຍົກເລີກ</option>
            </select>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b-2 border-primary/10">
                        <th class="px-5 py-4 font-bold text-primary text-[10px] tracking-widest uppercase">ເລກໃບບິນ</th>
                        <th class="px-5 py-4 font-bold text-primary text-[10px] tracking-widest uppercase">ນັກສຶກສາ</th>
                        <th class="px-5 py-4 font-bold text-primary text-[10px] tracking-widest uppercase w-32 text-center">ວັນທີ</th>
                        <th class="px-5 py-4 font-bold text-primary text-[10px] tracking-widest uppercase w-44 text-right">ຍອດລວມ</th>
                        <th class="px-5 py-4 font-bold text-primary text-[10px] tracking-widest uppercase w-36 text-center">ສະຖານະ</th>
                        <th class="px-5 py-4 font-bold text-primary text-[10px] tracking-widest uppercase w-32 text-center">ຊຳລະວັນທີ</th>
                        <th class="px-5 py-4 font-bold text-primary text-[10px] tracking-widest uppercase text-right w-36">ຈັດການ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/50 text-sm">
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-surface-container/40 transition-colors group">
                            <!-- Invoice number: ledger entry signature -->
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-0.5 h-8 bg-primary/15 group-hover:bg-primary rounded-full transition-all duration-200"></div>
                                    <span class="font-mono text-sm font-bold text-primary">{{ $invoice->invoice_number }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center shrink-0 border border-primary/10">
                                        <span class="text-xs font-bold text-primary">{{ mb_substr($invoice->student->gendered_name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="font-bold text-on-surface">{{ $invoice->student->gendered_name }}</div>
                                        <div class="text-[10px] text-outline font-semibold">{{ $invoice->student->student_id ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center text-xs text-outline font-medium">
                                {{ $invoice->date ? $invoice->date->format('d/m/Y') : '—' }}
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="font-bold text-primary">{{ number_format($invoice->total_amount) }}</div>
                                <div class="text-[10px] text-outline/70 font-medium">ກີບ (LAK)</div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($invoice->payment_status === 'paid')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-full border border-emerald-200">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full shrink-0"></span>
                                        ຊຳລະແລ້ວ
                                    </span>
                                @elseif($invoice->payment_status === 'unpaid')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-700 text-[10px] font-bold rounded-full border border-amber-200">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full shrink-0 animate-pulse"></span>
                                        ຍັງບໍ່ຊຳລະ
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 text-red-700 text-[10px] font-bold rounded-full border border-red-200">
                                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full shrink-0"></span>
                                        ຍົກເລີກ
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center text-xs text-outline font-medium">
                                {{ $invoice->payment_date ? $invoice->payment_date->format('d/m/Y') : '—' }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="openPaymentModal({{ $invoice->id }})"
                                            class="p-2 text-outline hover:text-primary hover:bg-surface-container rounded-lg transition-colors"
                                            title="ອັບເດດສະຖານະ">
                                        <span class="material-symbols-outlined text-[18px]">edit_document</span>
                                    </button>
                                    <a href="{{ route('invoices.pdf', $invoice->id) }}"
                                       target="_blank"
                                       class="p-2 text-outline hover:text-secondary hover:bg-surface-container rounded-lg transition-colors flex items-center justify-center"
                                       title="ພິມໃບບິນ PDF">
                                        <span class="material-symbols-outlined text-[18px]">print</span>
                                    </a>
                                    <button wire:click="deleteInvoice({{ $invoice->id }})"
                                            wire:confirm="ທ່ານແນ່ໃຈບໍ່ວ່າຕ້ອງການລຶບໃບບິນນີ້?"
                                            class="p-2 text-outline hover:text-error hover:bg-surface-container rounded-lg transition-colors"
                                            title="ລຶບໃບບິນ">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <span class="material-symbols-outlined text-5xl text-outline/30">receipt_long</span>
                                    <p class="text-sm font-bold text-outline">ບໍ່ມີໃບບິນ</p>
                                    <p class="text-xs text-outline/60">ຍັງບໍ່ມີຂໍ້ມູນໃບບິນທີ່ກົງກັບການຄົ້ນຫາ</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-surface-container-low border-t border-outline-variant/50">
            {{ $invoices->links() }}
        </div>
    </div>

    <!-- Modal 1: Create Invoice -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-on-background/50 backdrop-blur-sm">
            <div class="w-full max-w-xl bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                <!-- Modal Header -->
                <div class="px-6 py-5 border-b border-outline-variant bg-surface-container-low flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-[20px]">receipt_long</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-primary">ສ້າງໃບບິນຄ່າທຳນຽມໃໝ່</h4>
                            <p class="text-[10px] text-outline">ກຳນົດລາຄາ ແລະ ເລືອກນັກສຶກສາ</p>
                        </div>
                    </div>
                    <button wire:click="$set('showCreateModal', false)" class="p-1.5 hover:bg-surface-container-high rounded-lg transition-colors text-outline">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-6">
                    <form wire:submit.prevent="createInvoice" class="space-y-6">
                        <!-- Student Selection -->
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold text-primary/60 uppercase tracking-widest">ເລືອກນັກສຶກສາ *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-outline text-[18px] pointer-events-none">search</span>
                                <input wire:model.live.debounce.300ms="studentSearch"
                                       type="text"
                                       class="w-full bg-background border border-outline-variant rounded-lg pl-9 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-transparent mb-2"
                                       placeholder="ຄົ້ນຫາດ້ວຍລະຫັດ ຫຼື ຊື່..."/>
                            </div>
                            <select wire:model="student_id" class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">
                                    @if($studentSearch && $students->isEmpty())
                                        ບໍ່ພົບນັກສຶກສາ
                                    @else
                                        ເລືອກນັກສຶກສາ...
                                    @endif
                                </option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->student_id }} - {{ $student->gendered_name }} ({{ $student->major->name ?? 'N/A' }})</option>
                                @endforeach
                            </select>
                            @error('student_id') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Fee Breakdown -->
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <div class="h-px flex-1 bg-outline-variant/50"></div>
                                <span class="text-[10px] font-bold text-primary/50 uppercase tracking-widest">ລາຍລະອຽດຄ່າທຳນຽມ</span>
                                <div class="h-px flex-1 bg-outline-variant/50"></div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-on-surface">ຄ່າເຮັດບັດນັກສຶກສາ *</label>
                                    <div class="relative">
                                        <input type="text"
                                            x-data="{}"
                                            x-init="$el.value = Number(@js($card_fee)).toLocaleString('en-US')"
                                            @input="let raw=$event.target.value.replace(/[^0-9]/g,''); let num=raw?parseInt(raw):0; $event.target.value=num.toLocaleString('en-US'); $wire.set('card_fee',num);"
                                            @blur="let raw=$el.value.replace(/[^0-9]/g,''); $el.value=(raw?parseInt(raw):0).toLocaleString('en-US');"
                                            class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2.5 pr-12 text-sm focus:ring-2 focus:ring-primary" placeholder="20,000"/>
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-outline pointer-events-none">ກີບ</span>
                                    </div>
                                    @error('card_fee') <span class="text-xs text-error font-semibold">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-on-surface">ຄ່າຮູບຖ່າຍຕິດບັດ/ຟອມ *</label>
                                    <div class="relative">
                                        <input type="text"
                                            x-data="{}"
                                            x-init="$el.value = Number(@js($photo_fee)).toLocaleString('en-US')"
                                            @input="let raw=$event.target.value.replace(/[^0-9]/g,''); let num=raw?parseInt(raw):0; $event.target.value=num.toLocaleString('en-US'); $wire.set('photo_fee',num);"
                                            @blur="let raw=$el.value.replace(/[^0-9]/g,''); $el.value=(raw?parseInt(raw):0).toLocaleString('en-US');"
                                            class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2.5 pr-12 text-sm focus:ring-2 focus:ring-primary" placeholder="20,000"/>
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-outline pointer-events-none">ກີບ</span>
                                    </div>
                                    @error('photo_fee') <span class="text-xs text-error font-semibold">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-on-surface">ຄ່າລະບົບອີເມລວິທະຍາໄລ *</label>
                                    <div class="relative">
                                        <input type="text"
                                            x-data="{}"
                                            x-init="$el.value = Number(@js($email_fee)).toLocaleString('en-US')"
                                            @input="let raw=$event.target.value.replace(/[^0-9]/g,''); let num=raw?parseInt(raw):0; $event.target.value=num.toLocaleString('en-US'); $wire.set('email_fee',num);"
                                            @blur="let raw=$el.value.replace(/[^0-9]/g,''); $el.value=(raw?parseInt(raw):0).toLocaleString('en-US');"
                                            class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2.5 pr-12 text-sm focus:ring-2 focus:ring-primary" placeholder="50,000"/>
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-outline pointer-events-none">ກີບ</span>
                                    </div>
                                    @error('email_fee') <span class="text-xs text-error font-semibold">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-on-surface">ຄ່າຮຽນ/ຄ່າລົງທະບຽນເທີມ *</label>
                                    <div class="relative">
                                        <input type="text"
                                            x-data="{}"
                                            x-init="$el.value = Number(@js($tuition_fee)).toLocaleString('en-US')"
                                            @input="let raw=$event.target.value.replace(/[^0-9]/g,''); let num=raw?parseInt(raw):0; $event.target.value=num.toLocaleString('en-US'); $wire.set('tuition_fee',num);"
                                            @blur="let raw=$el.value.replace(/[^0-9]/g,''); $el.value=(raw?parseInt(raw):0).toLocaleString('en-US');"
                                            class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2.5 pr-12 text-sm focus:ring-2 focus:ring-primary" placeholder="0"/>
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-outline pointer-events-none">ກີບ</span>
                                    </div>
                                    @error('tuition_fee') <span class="text-xs text-error font-semibold">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Bank & Notes -->
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <div class="h-px flex-1 bg-outline-variant/50"></div>
                                <span class="text-[10px] font-bold text-primary/50 uppercase tracking-widest">ການຕັ້ງຄ່າໂອນເງິນ</span>
                                <div class="h-px flex-1 bg-outline-variant/50"></div>
                            </div>
                            <div class="space-y-4">
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-on-surface">ເລກບັນຊີທະນາຄານຮັບໂອນ (BCEL) *</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-outline text-[18px] pointer-events-none">account_balance</span>
                                        <input wire:model="bank_account_number" type="text" class="w-full bg-background border border-outline-variant rounded-lg pl-9 pr-4 py-2.5 text-sm font-mono focus:ring-2 focus:ring-primary" placeholder="01452026000028"/>
                                    </div>
                                    @error('bank_account_number') <span class="text-xs text-error font-semibold">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-on-surface">ໝາຍເຫດ/ຄຳອະທິບາຍເພີ່ມເຕີມ</label>
                                    <textarea wire:model="notes" rows="2" class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary resize-none" placeholder="ເຊັ່ນ: ຄ່າທຳນຽມເຂົ້າຮຽນໃໝ່ ພາກຮຽນ 1 ປີ 1..."></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer - Total -->
                <div class="px-6 py-5 border-t border-outline-variant bg-primary/5">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-primary/50 uppercase tracking-widest">ຍອດລວມທັງໝົດ</p>
                            <p class="text-2xl font-bold text-primary mt-0.5">
                                {{ number_format(floatval($card_fee) + floatval($photo_fee) + floatval($email_fee) + floatval($tuition_fee)) }}
                                <span class="text-sm font-medium text-outline ml-1">ກີບ</span>
                            </p>
                        </div>
                        <div class="flex gap-3">
                            <button wire:click="$set('showCreateModal', false)"
                                    class="px-5 py-2.5 border border-outline-variant hover:bg-surface-container rounded-lg text-sm text-on-surface font-bold transition-all">
                                ຍົກເລີກ
                            </button>
                            <button wire:click="createInvoice"
                                    class="px-5 py-2.5 bg-primary hover:bg-primary-container text-on-primary rounded-lg text-sm font-bold shadow-md transition-all flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                                ສ້າງໃບບິນ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal 2: Update Payment Status -->
    @if($showPaymentModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-on-background/50 backdrop-blur-sm">
            <div class="w-full max-w-sm bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-2xl overflow-hidden">
                <!-- Modal Header -->
                <div class="px-6 py-5 border-b border-outline-variant bg-surface-container-low flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-[20px]">edit_document</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-primary">ອັບເດດສະຖານະ</h4>
                            <p class="text-[10px] text-outline">ເລືອກສະຖານະການຊຳລະເງິນ</p>
                        </div>
                    </div>
                    <button wire:click="$set('showPaymentModal', false)" class="p-1.5 hover:bg-surface-container-high rounded-lg transition-colors text-outline">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <!-- Radio Cards -->
                <div class="p-6 space-y-3">
                    <!-- Paid -->
                    <label class="cursor-pointer block" wire:click="$set('payment_status', 'paid')">
                        <input type="radio" wire:model="payment_status" value="paid" class="sr-only">
                        <div class="border-2 rounded-xl p-4 flex items-center gap-3 transition-all {{ $payment_status === 'paid' ? 'border-emerald-500 bg-emerald-50' : 'border-emerald-200/60 bg-emerald-50/30 hover:border-emerald-300 hover:bg-emerald-50/60' }}">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 {{ $payment_status === 'paid' ? 'bg-emerald-100' : 'bg-emerald-100/50' }}">
                                <span class="material-symbols-outlined text-[20px] {{ $payment_status === 'paid' ? 'text-emerald-600' : 'text-emerald-400' }}">check_circle</span>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-bold {{ $payment_status === 'paid' ? 'text-emerald-800' : 'text-on-surface/70' }}">ຊຳລະແລ້ວ</div>
                                <div class="text-[10px] text-outline">Paid</div>
                            </div>
                            <span class="material-symbols-outlined text-[18px] {{ $payment_status === 'paid' ? 'text-emerald-500' : 'text-outline/40' }}">
                                {{ $payment_status === 'paid' ? 'radio_button_checked' : 'radio_button_unchecked' }}
                            </span>
                        </div>
                    </label>

                    <!-- Unpaid -->
                    <label class="cursor-pointer block" wire:click="$set('payment_status', 'unpaid')">
                        <input type="radio" wire:model="payment_status" value="unpaid" class="sr-only">
                        <div class="border-2 rounded-xl p-4 flex items-center gap-3 transition-all {{ $payment_status === 'unpaid' ? 'border-amber-400 bg-amber-50' : 'border-amber-200/60 bg-amber-50/30 hover:border-amber-300 hover:bg-amber-50/60' }}">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 {{ $payment_status === 'unpaid' ? 'bg-amber-100' : 'bg-amber-100/50' }}">
                                <span class="material-symbols-outlined text-[20px] {{ $payment_status === 'unpaid' ? 'text-amber-600' : 'text-amber-400' }}">pending</span>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-bold {{ $payment_status === 'unpaid' ? 'text-amber-800' : 'text-on-surface/70' }}">ຍັງບໍ່ຊຳລະ</div>
                                <div class="text-[10px] text-outline">Unpaid</div>
                            </div>
                            <span class="material-symbols-outlined text-[18px] {{ $payment_status === 'unpaid' ? 'text-amber-500' : 'text-outline/40' }}">
                                {{ $payment_status === 'unpaid' ? 'radio_button_checked' : 'radio_button_unchecked' }}
                            </span>
                        </div>
                    </label>

                    <!-- Cancelled -->
                    <label class="cursor-pointer block" wire:click="$set('payment_status', 'cancelled')">
                        <input type="radio" wire:model="payment_status" value="cancelled" class="sr-only">
                        <div class="border-2 rounded-xl p-4 flex items-center gap-3 transition-all {{ $payment_status === 'cancelled' ? 'border-red-400 bg-red-50' : 'border-red-200/60 bg-red-50/30 hover:border-red-300 hover:bg-red-50/60' }}">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 {{ $payment_status === 'cancelled' ? 'bg-red-100' : 'bg-red-100/50' }}">
                                <span class="material-symbols-outlined text-[20px] {{ $payment_status === 'cancelled' ? 'text-red-600' : 'text-red-400' }}">cancel</span>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-bold {{ $payment_status === 'cancelled' ? 'text-red-800' : 'text-on-surface/70' }}">ຍົກເລີກ</div>
                                <div class="text-[10px] text-outline">Cancelled</div>
                            </div>
                            <span class="material-symbols-outlined text-[18px] {{ $payment_status === 'cancelled' ? 'text-red-500' : 'text-outline/40' }}">
                                {{ $payment_status === 'cancelled' ? 'radio_button_checked' : 'radio_button_unchecked' }}
                            </span>
                        </div>
                    </label>

                    <!-- Payment Date (shown when paid) -->
                    @if($payment_status === 'paid')
                        <div class="pt-1 space-y-1.5">
                            <label class="block text-xs font-bold text-on-surface">ວັນທີຊຳລະເງິນ *</label>
                            <input wire:model="payment_date" type="date" class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary"/>
                        </div>
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-5 border-t border-outline-variant bg-surface-container-low flex justify-end gap-3">
                    <button wire:click="$set('showPaymentModal', false)"
                            class="px-5 py-2.5 border border-outline-variant hover:bg-surface-container-high rounded-lg text-sm text-on-surface font-bold transition-all">
                        ຍົກເລີກ
                    </button>
                    <button wire:click="updatePaymentStatus"
                            class="px-5 py-2.5 bg-primary hover:bg-primary-container text-on-primary rounded-lg text-sm font-bold shadow-md transition-all">
                        ບັນທຶກ
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
