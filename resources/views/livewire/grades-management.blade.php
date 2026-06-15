<div>
    <!-- Header Banner -->
    <div class="relative overflow-hidden rounded-2xl bg-primary text-on-primary p-8 mb-8 flex flex-col md:flex-row justify-between items-center shadow-lg">
        <!-- Gold left accent stripe -->
        <div class="absolute left-0 top-0 h-full w-1.5 bg-secondary-container rounded-l-2xl"></div>

        <div class="relative z-10 pl-4">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-secondary-container text-[18px]">school</span>
                <span class="text-secondary-container text-[10px] font-bold uppercase tracking-widest">ລະບົບຄຸ້ມຄອງວິຊາການ</span>
            </div>
            <h2 class="text-3xl font-bold text-white mb-2">ການລົງທະບຽນ ແລະ ຄະແນນ</h2>
            <p class="text-sm text-white/65 max-w-2xl">
                ລົງທະບຽນຮຽນ ແລະ ປ້ອນຄະແນນພາກຮຽນ — ລະບົບຄຳນວນເກຣດໂດຍອັດຕະໂນມັດ.
            </p>
        </div>

        <div class="mt-6 md:mt-0 relative z-10 flex gap-3">
            @if($activeTab === 'enrollments')
                <button wire:click="openBulkEnrollModal"
                        class="bg-secondary-container text-secondary px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 hover:brightness-105 active:scale-[0.99] transition-all shadow-md text-sm">
                    <span class="material-symbols-outlined text-[20px]">group_add</span>
                    <span>ລົງທະບຽນເປັນກຸ່ມ</span>
                </button>
                <button wire:click="openEnrollModal"
                        class="bg-white/10 border border-white/25 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 hover:bg-white/20 active:scale-[0.99] transition-all shadow-md text-sm">
                    <span class="material-symbols-outlined text-[20px]">person_add</span>
                    <span>ລາຍບຸກຄົນ</span>
                </button>
            @endif
        </div>

        <!-- Decorative watermark -->
        <div class="absolute right-4 top-0 h-full flex items-center opacity-[0.05] pointer-events-none">
            <span class="material-symbols-outlined text-white" style="font-size: 200px; font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 48;">grade</span>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-xl text-sm flex items-center gap-3 shadow-sm">
            <span class="material-symbols-outlined text-emerald-600" style="font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;">check_circle</span>
            <span class="font-semibold">{{ session('message') }}</span>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-error text-red-800 rounded-r-xl text-sm flex items-center gap-3 shadow-sm">
            <span class="material-symbols-outlined text-error" style="font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;">error</span>
            <span class="font-semibold">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Tabs Section -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl overflow-hidden shadow-sm">

        <!-- Tab Navigation -->
        <div class="flex bg-surface-container-low border-b border-outline-variant p-3 gap-2">
            <button wire:click="switchTab('enrollments')"
                    class="px-5 py-2 text-sm font-bold rounded-lg transition-all flex items-center gap-2 {{ $activeTab === 'enrollments' ? 'bg-primary text-on-primary shadow-sm' : 'text-outline hover:text-on-background hover:bg-surface-container' }}">
                <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
                ການລົງທະບຽນຮຽນ
            </button>
            <button wire:click="switchTab('grading')"
                    class="px-5 py-2 text-sm font-bold rounded-lg transition-all flex items-center gap-2 {{ $activeTab === 'grading' ? 'bg-primary text-on-primary shadow-sm' : 'text-outline hover:text-on-background hover:bg-surface-container' }}">
                <span class="material-symbols-outlined text-[18px]">assignment</span>
                ຕາຕະລາງປ້ອນຄະແນນ
            </button>
        </div>

        <!-- ==================== Tab 1: Enrollments ==================== -->
        @if($activeTab === 'enrollments')
            <div class="p-6">
                <!-- Filters -->
                <div class="bg-white border border-outline-variant rounded-xl p-4 flex flex-wrap gap-4 mb-6 items-end shadow-sm">
                    <!-- Search -->
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">ຄົ້ນຫານັກສຶກສາ</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px]">search</span>
                            <input wire:model.live.debounce.300ms="searchEnrollment"
                                   type="text"
                                   class="w-full bg-surface-container-low border border-outline-variant rounded-lg pl-9 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:bg-white transition-colors"
                                   placeholder="ຊື່, ນາມສະກຸນ ຫຼື ລະຫັດ..."/>
                        </div>
                    </div>
                    <!-- Major -->
                    <div class="w-56 min-w-[150px]">
                        <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">ສາຂາວິຊາ</label>
                        <select wire:model.live="filterMajor" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:bg-white">
                            <option value="">ທັງໝົດ</option>
                            @foreach($majors as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Subject -->
                    <div class="w-60 min-w-[150px]">
                        <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">ວິຊາຮຽນ</label>
                        <select wire:model.live="filterSubject" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:bg-white">
                            <option value="">ທັງໝົດ</option>
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}">{{ $s->subject_code }} - {{ $s->subject_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Academic Year -->
                    <div class="w-40 min-w-[120px]">
                        <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">ສົກຮຽນ</label>
                        <select wire:model.live="filterYear" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:bg-white">
                            <option value="">ທັງໝົດ</option>
                            @foreach($academicYears as $y)
                                <option value="{{ $y->id }}">{{ $y->year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Enrollments Table -->
                <div class="overflow-x-auto border border-outline-variant rounded-xl">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low border-b-2 border-outline-variant text-[10px] font-bold text-outline uppercase tracking-widest">
                                <th class="px-4 py-3 w-36">ລະຫັດ</th>
                                <th class="px-4 py-3">ນັກສຶກສາ</th>
                                <th class="px-4 py-3">ສາຂາ</th>
                                <th class="px-4 py-3">ວິຊາຮຽນ</th>
                                <th class="px-4 py-3 w-24 text-center">ພາກ</th>
                                <th class="px-4 py-3 w-32 text-center">ສົກຮຽນ</th>
                                <th class="px-4 py-3 w-36 text-center">ວັນທີ່</th>
                                <th class="px-4 py-3 w-32 text-center">ສະຖານະ</th>
                                <th class="px-4 py-3 w-16 text-center">ຈັດການ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant text-sm">
                            @forelse($enrollments as $enroll)
                                <tr class="hover:bg-primary/[0.025] transition-colors group">
                                    <td class="px-4 py-3 font-mono font-bold text-primary text-xs">{{ $enroll->student->student_id ?? '-' }}</td>
                                    <td class="px-4 py-3 font-bold text-on-background">{{ $enroll->student->gendered_name }}</td>
                                    <td class="px-4 py-3 text-xs text-outline font-medium">{{ $enroll->student->major->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-col gap-0.5">
                                            <span class="font-bold text-primary text-xs font-mono">{{ $enroll->subject->subject_code }}</span>
                                            <span class="text-xs text-outline">{{ $enroll->subject->subject_name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-primary/10 text-primary text-xs font-bold">{{ $enroll->semester }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm font-semibold">{{ $enroll->academicYear->year }}</td>
                                    <td class="px-4 py-3 text-center text-xs text-outline">{{ $enroll->enrollment_date ? $enroll->enrollment_date->format('d/m/Y') : '-' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        @if($enroll->status === 'enrolled')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 text-[10px] font-bold rounded-full border border-blue-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 flex-shrink-0"></span>ລົງທະບຽນ
                                            </span>
                                        @elseif($enroll->status === 'completed')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-full border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 flex-shrink-0"></span>ຜ່ານວິຊາ
                                            </span>
                                        @elseif($enroll->status === 'failed')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-50 text-red-700 text-[10px] font-bold rounded-full border border-red-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 flex-shrink-0"></span>ຕົກວິຊາ
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-surface-container text-outline text-[10px] font-bold rounded-full border border-outline-variant">{{ $enroll->status }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button wire:click="deleteEnrollment({{ $enroll->id }})"
                                                wire:confirm="ທ່ານແນ່ໃຈບໍ່ວ່າຕ້ອງການຍົກເລີກການລົງທະບຽນວິຊານີ້ຂອງນັກສຶກສາຄົນນີ້? (ຄະແນນທີ່ກ່ຽວຂ້ອງຈະຖືກລຶບອອກນຳ)"
                                                class="p-1.5 text-outline/40 hover:text-error rounded-lg hover:bg-red-50 transition-all opacity-0 group-hover:opacity-100"
                                                title="ຍົກເລີກການລົງທະບຽນ">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <span class="material-symbols-outlined text-outline/25 block" style="font-size: 52px; font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 48;">person_search</span>
                                            <p class="text-sm font-bold text-outline">ບໍ່ພົບຂໍ້ມູນການລົງທະບຽນ</p>
                                            <p class="text-xs text-outline/70">ກະລຸນາປ່ຽນເງື່ອນໄຂຄົ້ນຫາ ຫຼື ລົງທະບຽນນັກສຶກສາໃໝ່</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $enrollments->links() }}
                </div>
            </div>
        @endif

        <!-- ==================== Tab 2: Grading Sheet ==================== -->
        @if($activeTab === 'grading')
            <div class="p-6">
                <!-- Course Selector -->
                <div class="bg-white border border-outline-variant rounded-xl p-5 mb-6 shadow-sm">
                    <p class="text-[10px] font-bold text-outline uppercase tracking-widest mb-4 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[14px] text-primary">tune</span>
                        ເລືອກລາຍວິຊາ ແລະ ພາກຮຽນ
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div class="md:col-span-2 space-y-1.5">
                            <label class="block text-xs font-bold text-primary">ວິຊາຮຽນ *</label>
                            <select wire:model="selectedSubject" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white">
                                <option value="">ເລືອກວິຊາຮຽນ...</option>
                                @foreach($subjects as $s)
                                    <option value="{{ $s->id }}">{{ $s->subject_code }} - {{ $s->subject_name }} ({{ $s->credits }} ໜ່ວຍກິດ)</option>
                                @endforeach
                            </select>
                            @error('selectedSubject') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-primary">ສົກຮຽນ *</label>
                            <select wire:model="selectedAcademicYear" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white">
                                <option value="">ເລືອກສົກຮຽນ...</option>
                                @foreach($academicYears as $y)
                                    <option value="{{ $y->id }}">{{ $y->year }}</option>
                                @endforeach
                            </select>
                            @error('selectedAcademicYear') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-primary">ພາກຮຽນ *</label>
                            <select wire:model="selectedSemester" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white">
                                @for($i=1; $i<=8; $i++)
                                    <option value="{{ $i }}">ພາກຮຽນ {{ $i }}</option>
                                @endfor
                            </select>
                            @error('selectedSemester') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mt-5 flex justify-end">
                        <button wire:click="loadGradingSheet"
                                class="bg-primary text-on-primary px-6 py-2.5 rounded-lg text-sm font-bold shadow-sm hover:bg-primary-container hover:shadow-md active:scale-[0.99] transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">table_view</span>
                            <span>ໂຫຼດຕາຕະລາງຄະແນນ</span>
                        </button>
                    </div>
                </div>

                <!-- Grading Alerts -->
                @if (session()->has('message_grading'))
                    <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-xl text-sm flex items-center gap-3 shadow-sm">
                        <span class="material-symbols-outlined text-emerald-600" style="font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;">check_circle</span>
                        <span class="font-semibold">{{ session('message_grading') }}</span>
                    </div>
                @endif
                @if (session()->has('error_grading'))
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-error text-red-800 rounded-r-xl text-sm flex items-center gap-3 shadow-sm">
                        <span class="material-symbols-outlined text-error" style="font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;">error</span>
                        <span class="font-semibold">{{ session('error_grading') }}</span>
                    </div>
                @endif

                <!-- Grading Matrix -->
                @if(!empty($scores))
                    <div class="border border-outline-variant rounded-xl overflow-hidden shadow-sm">

                        <!-- Matrix Toolbar -->
                        <div class="px-5 py-4 bg-primary flex justify-between items-center">
                            <div>
                                <h4 class="text-sm font-bold text-white">ຕາຕະລາງປ້ອນຄະແນນ</h4>
                                <p class="text-[11px] text-white/55 mt-0.5">ເຂົ້າຮ່ວມ(10) + ບຸກຄົນ(15) + ກຸ່ມ(15) + ກາງພາກ(20) + ທ້າຍພາກ(40) = 100 ຄະແນນ</p>
                            </div>
                            <button wire:click="saveGrades"
                                    class="bg-secondary-container text-secondary px-5 py-2 rounded-lg text-sm font-bold hover:brightness-105 active:scale-[0.99] transition-all flex items-center gap-2 shadow-sm">
                                <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;">save</span>
                                <span>ບັນທຶກ ແລະ ຄຳນວນເກຣດ</span>
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[1000px]">
                                <thead>
                                    <tr class="border-b-2 border-outline-variant bg-surface-container-low text-[10px] font-bold text-outline uppercase tracking-wider text-center">
                                        <th class="px-4 py-3 text-left w-36">ລະຫັດ</th>
                                        <th class="px-4 py-3 text-left w-52">ຊື່ ແລະ ນາມສະກຸນ</th>
                                        <th class="px-3 py-3 w-24">ເຂົ້າຮ່ວມ<br><span class="text-primary/50 font-normal normal-case">/10</span></th>
                                        <th class="px-3 py-3 w-24">ບຸກຄົນ<br><span class="text-primary/50 font-normal normal-case">/15</span></th>
                                        <th class="px-3 py-3 w-24">ກຸ່ມ<br><span class="text-primary/50 font-normal normal-case">/15</span></th>
                                        <th class="px-3 py-3 w-24">ກາງພາກ<br><span class="text-primary/50 font-normal normal-case">/20</span></th>
                                        <th class="px-3 py-3 w-24">ທ້າຍພາກ<br><span class="text-primary/50 font-normal normal-case">/40</span></th>
                                        <th class="px-3 py-3 w-20 bg-primary/[0.06] text-primary">ລວມ</th>
                                        <th class="px-3 py-3 w-20 bg-primary/[0.06] text-primary">ເກຣດ</th>
                                        <th class="px-4 py-3 text-left">ໝາຍເຫດ</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-outline-variant text-xs">
                                    @foreach($scores as $enrollmentId => $score)
                                        @php
                                            $mid  = is_numeric($score['midterm'])       ? min(20.0, floatval($score['midterm']))       : 0;
                                            $fin  = is_numeric($score['final'])         ? min(40.0, floatval($score['final']))         : 0;
                                            $ass  = is_numeric($score['assignment'])    ? min(15.0, floatval($score['assignment']))    : 0;
                                            $part = is_numeric($score['participation']) ? min(10.0, floatval($score['participation'])) : 0;
                                            $proj = is_numeric($score['project'])       ? min(15.0, floatval($score['project']))       : 0;
                                            $currTotal = min(100, $mid + $fin + $ass + $part + $proj);

                                            if      ($currTotal >= 85) { $previewLetter = 'A';  $gradeClass = 'bg-emerald-100 text-emerald-800 ring-1 ring-emerald-300'; }
                                            elseif  ($currTotal >= 80) { $previewLetter = 'B+'; $gradeClass = 'bg-teal-100 text-teal-800 ring-1 ring-teal-300'; }
                                            elseif  ($currTotal >= 75) { $previewLetter = 'B';  $gradeClass = 'bg-teal-50 text-teal-700 ring-1 ring-teal-200'; }
                                            elseif  ($currTotal >= 70) { $previewLetter = 'C+'; $gradeClass = 'bg-amber-100 text-amber-800 ring-1 ring-amber-300'; }
                                            elseif  ($currTotal >= 65) { $previewLetter = 'C';  $gradeClass = 'bg-amber-50 text-amber-700 ring-1 ring-amber-200'; }
                                            elseif  ($currTotal >= 60) { $previewLetter = 'D+'; $gradeClass = 'bg-orange-100 text-orange-800 ring-1 ring-orange-300'; }
                                            elseif  ($currTotal >= 55) { $previewLetter = 'D';  $gradeClass = 'bg-orange-50 text-orange-700 ring-1 ring-orange-200'; }
                                            else                        { $previewLetter = 'F';  $gradeClass = 'bg-red-100 text-red-800 ring-1 ring-red-300'; }
                                        @endphp
                                        <tr class="hover:bg-primary/[0.02] transition-colors text-center">
                                            <td class="px-4 py-2.5 text-left font-mono font-bold text-primary text-[11px]">{{ $score['student_code'] ?? '-' }}</td>
                                            <td class="px-4 py-2.5 text-left font-bold text-on-background text-xs">{{ $score['student_name'] }}</td>
                                            <td class="px-2 py-2">
                                                <input wire:model.live="scores.{{ $enrollmentId }}.participation" type="number" step="0.5" min="0" max="10"
                                                       class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-2 py-1.5 text-xs text-center focus:ring-2 focus:ring-primary focus:bg-white transition-colors"/>
                                            </td>
                                            <td class="px-2 py-2">
                                                <input wire:model.live="scores.{{ $enrollmentId }}.assignment" type="number" step="0.5" min="0" max="15"
                                                       class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-2 py-1.5 text-xs text-center focus:ring-2 focus:ring-primary focus:bg-white transition-colors"/>
                                            </td>
                                            <td class="px-2 py-2">
                                                <input wire:model.live="scores.{{ $enrollmentId }}.project" type="number" step="0.5" min="0" max="15"
                                                       class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-2 py-1.5 text-xs text-center focus:ring-2 focus:ring-primary focus:bg-white transition-colors"/>
                                            </td>
                                            <td class="px-2 py-2">
                                                <input wire:model.live="scores.{{ $enrollmentId }}.midterm" type="number" step="0.5" min="0" max="20"
                                                       class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-2 py-1.5 text-xs text-center focus:ring-2 focus:ring-primary focus:bg-white transition-colors"/>
                                            </td>
                                            <td class="px-2 py-2">
                                                <input wire:model.live="scores.{{ $enrollmentId }}.final" type="number" step="0.5" min="0" max="40"
                                                       class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-2 py-1.5 text-xs text-center focus:ring-2 focus:ring-primary focus:bg-white transition-colors"/>
                                            </td>
                                            <td class="px-3 py-2.5 font-bold bg-primary/[0.04] text-primary text-sm">{{ $currTotal }}</td>
                                            <td class="px-3 py-2.5 bg-primary/[0.04]">
                                                <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold {{ $gradeClass }}">{{ $previewLetter }}</span>
                                            </td>
                                            <td class="px-2 py-2 text-left">
                                                <input wire:model="scores.{{ $enrollmentId }}.remarks" type="text"
                                                       class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-3 py-1.5 text-xs focus:ring-2 focus:ring-primary focus:bg-white transition-colors"
                                                       placeholder="ໝາຍເຫດ..."/>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Footer bar -->
                        <div class="px-5 py-4 bg-surface-container-low border-t border-outline-variant flex justify-between items-center">
                            <span class="text-xs text-outline font-semibold flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px]">groups</span>
                                ນັກສຶກສາທັງໝົດ: <strong class="text-primary ml-1">{{ count($scores) }} ຄົນ</strong>
                            </span>
                            <button wire:click="saveGrades" class="bg-primary hover:bg-primary-container text-on-primary px-7 py-2.5 rounded-lg text-sm font-bold shadow-sm hover:shadow-md active:scale-[0.99] transition-all flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;">save</span>
                                <span>ບັນທຶກ ແລະ ຄຳນວນເກຣດທັງໝົດ</span>
                            </button>
                        </div>

                        <!-- Grade Scale Legend -->
                        <div class="px-5 py-4 border-t border-outline-variant bg-surface-container-low/50 rounded-b-xl">
                            <p class="text-[10px] font-bold text-outline uppercase tracking-widest mb-2.5 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[14px]">legend_toggle</span>
                                ເກນການໃຫ້ເກຣດ
                            </p>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-800 ring-1 ring-emerald-300">A <span class="font-normal text-emerald-600">85–100 · 4.00</span></span>
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-lg bg-teal-100 text-teal-800 ring-1 ring-teal-300">B+ <span class="font-normal text-teal-600">80–84 · 3.50</span></span>
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-lg bg-teal-50 text-teal-700 ring-1 ring-teal-200">B <span class="font-normal text-teal-500">75–79 · 3.00</span></span>
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-lg bg-amber-100 text-amber-800 ring-1 ring-amber-300">C+ <span class="font-normal text-amber-600">70–74 · 2.50</span></span>
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 ring-1 ring-amber-200">C <span class="font-normal text-amber-500">65–69 · 2.00</span></span>
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-lg bg-orange-100 text-orange-800 ring-1 ring-orange-300">D+ <span class="font-normal text-orange-600">60–64 · 1.50</span></span>
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-lg bg-orange-50 text-orange-700 ring-1 ring-orange-200">D <span class="font-normal text-orange-500">55–59 · 1.00</span></span>
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-lg bg-red-100 text-red-800 ring-1 ring-red-300">F <span class="font-normal text-red-600">0–54 · 0.00</span></span>
                            </div>
                        </div>
                    </div>

                @else
                    <!-- Empty State -->
                    <div class="bg-white border border-outline-variant rounded-xl py-16 text-center">
                        <span class="material-symbols-outlined text-outline/20 block mb-3" style="font-size: 56px; font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 48;">assignment</span>
                        <p class="text-sm font-bold text-on-background/40 mb-1">ຍັງບໍ່ໄດ້ໂຫຼດຕາຕະລາງຄະແນນ</p>
                        <p class="text-xs text-outline/60">ເລືອກ ວິຊາ, ສົກຮຽນ ແລະ ພາກຮຽນ ດ້ານເທິງ ແລ້ວກົດ "ໂຫຼດຕາຕະລາງ"</p>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Modal: Single Enrollment -->
    @if($showEnrollModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-on-background/50 backdrop-blur-sm">
            <div class="w-full max-w-md bg-white border border-outline-variant rounded-2xl shadow-2xl overflow-hidden flex flex-col">
                <div class="px-6 py-4 bg-primary flex justify-between items-center">
                    <h4 class="text-base font-bold text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;">person_add</span>
                        ລົງທະບຽນລາຍບຸກຄົນ
                    </h4>
                    <button wire:click="$set('showEnrollModal', false)" class="p-1 hover:bg-white/15 rounded-lg transition-colors text-white/60 hover:text-white">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-primary">ເລືອກນັກສຶກສາ *</label>
                        <select wire:model="enroll_student_id" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white">
                            <option value="">ເລືອກນັກສຶກສາ...</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->student_id }} - {{ $student->gendered_name }} ({{ $student->major->name ?? 'N/A' }})</option>
                            @endforeach
                        </select>
                        @error('enroll_student_id') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-primary">ເລືອກວິຊາຮຽນ *</label>
                        <select wire:model="enroll_subject_id" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white">
                            <option value="">ເລືອກວິຊາຮຽນ...</option>
                            @foreach($subjects as $subj)
                                <option value="{{ $subj->id }}">{{ $subj->subject_code }} - {{ $subj->subject_name }}</option>
                            @endforeach
                        </select>
                        @error('enroll_subject_id') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-primary">ສົກຮຽນ *</label>
                            <select wire:model="enroll_academic_year_id" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:bg-white">
                                <option value="">ເລືອກສົກ...</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}">{{ $year->year }}</option>
                                @endforeach
                            </select>
                            @error('enroll_academic_year_id') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-primary">ພາກຮຽນ *</label>
                            <select wire:model="enroll_semester" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:bg-white">
                                @for($i=1; $i<=8; $i++)
                                    <option value="{{ $i }}">ພາກຮຽນ {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-outline-variant bg-surface-container-low flex justify-end gap-3">
                    <button wire:click="$set('showEnrollModal', false)" class="px-5 py-2 border border-outline-variant hover:bg-surface-container-high rounded-lg text-sm text-primary font-bold transition-all">ຍົກເລີກ</button>
                    <button wire:click="enrollStudent" class="px-5 py-2 bg-primary hover:bg-primary-container text-on-primary rounded-lg text-sm font-bold shadow-sm hover:shadow-md transition-all">ຢືນຢັນການລົງທະບຽນ</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal: Bulk Enrollment -->
    @if($showBulkEnrollModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-on-background/50 backdrop-blur-sm">
            <div class="w-full max-w-md bg-white border border-outline-variant rounded-2xl shadow-2xl overflow-hidden flex flex-col">
                <div class="px-6 py-4 bg-secondary flex justify-between items-center">
                    <h4 class="text-base font-bold text-on-secondary flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;">group_add</span>
                        ລົງທະບຽນເປັນກຸ່ມ
                    </h4>
                    <button wire:click="$set('showBulkEnrollModal', false)" class="p-1 hover:bg-black/10 rounded-lg transition-colors text-on-secondary/60 hover:text-on-secondary">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div class="p-3.5 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl text-xs flex items-start gap-2 font-medium">
                        <span class="material-symbols-outlined text-amber-600 text-[16px] mt-0.5 flex-shrink-0" style="font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;">info</span>
                        <span>ລະບົບຈະຊອກຫານັກສຶກສາຕາມ <strong>ສາຂາວິຊາ</strong> ແລະ <strong>ສົກຮຽນທີ່ນັກສຶກສາເຂົ້າ</strong> ຈາກນັ້ນລົງທະບຽນວິຊານີ້ໃຫ້ທັງໝົດ ພາຍໃຕ້ <strong>ສົກຮຽນທີ່ຈະລົງທະບຽນ</strong>.</span>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-primary">ສາຂາວິຊາ *</label>
                        <select wire:model="bulk_major_id" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:bg-white">
                            <option value="">ເລືອກສາຂາ...</option>
                            @foreach($majors as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                        @error('bulk_major_id') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-primary">ສົກທີ່ນັກສຶກສາເຂົ້າ *</label>
                            <select wire:model="bulk_academic_year_id" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:bg-white">
                                <option value="">ເລືອກສົກ...</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}">{{ $year->year }}</option>
                                @endforeach
                            </select>
                            @error('bulk_academic_year_id') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-primary">ສົກທີ່ຈະລົງທະບຽນ *</label>
                            <select wire:model="bulk_enroll_year_id" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:bg-white">
                                <option value="">ເລືອກສົກ...</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}">{{ $year->year }}</option>
                                @endforeach
                            </select>
                            @error('bulk_enroll_year_id') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-primary">ພາກຮຽນທີ່ລົງທະບຽນ *</label>
                        <select wire:model="bulk_semester" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:bg-white">
                            @for($i=1; $i<=8; $i++)
                                <option value="{{ $i }}">ພາກຮຽນ {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-primary">ວິຊາທີ່ຈະລົງທະບຽນ *</label>
                        <select wire:model="bulk_subject_id" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:bg-white">
                            <option value="">ເລືອກວິຊາ...</option>
                            @foreach($subjects as $subj)
                                <option value="{{ $subj->id }}">{{ $subj->subject_code }} - {{ $subj->subject_name }}</option>
                            @endforeach
                        </select>
                        @error('bulk_subject_id') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-outline-variant bg-surface-container-low flex justify-end gap-3">
                    <button wire:click="$set('showBulkEnrollModal', false)" class="px-5 py-2 border border-outline-variant hover:bg-surface-container-high rounded-lg text-sm text-primary font-bold transition-all">ຍົກເລີກ</button>
                    <button wire:click="bulkEnroll" class="px-5 py-2 bg-primary hover:bg-primary-container text-on-primary rounded-lg text-sm font-bold shadow-sm hover:shadow-md transition-all">ລົງທະບຽນທັງໝົດ</button>
                </div>
            </div>
        </div>
    @endif
</div>
