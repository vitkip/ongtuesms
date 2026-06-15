<div>
    <!-- Header -->
    <div class="relative overflow-hidden rounded-2xl bg-primary text-white p-8 mb-8 shadow-lg">
        <div class="absolute inset-0 opacity-5 pointer-events-none">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs><pattern id="acad-grid" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                </pattern></defs>
                <rect width="100%" height="100%" fill="url(#acad-grid)"/>
            </svg>
        </div>
        <div class="absolute right-8 top-1/2 -translate-y-1/2 opacity-10 pointer-events-none hidden md:block">
            <span class="material-symbols-outlined" style="font-size: 160px;">school</span>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <p class="text-white/60 text-[10px] font-bold uppercase tracking-widest mb-1">ວິທະຍາໄລຄູສົງ ອົງຕື້</p>
                <h2 class="text-3xl font-bold text-white mb-1.5">ຈັດການວິຊາການ ແລະ ຫຼັກສູດ</h2>
                <p class="text-sm text-white/70 max-w-lg">ຈັດການສາຂາວິຊາ, ໂຄງສ້າງຫຼັກສູດ ແລະ ລາຍວິຊາຮຽນ</p>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 text-center min-w-[76px] border border-white/20">
                    <div class="text-2xl font-bold">{{ count($majorsList) }}</div>
                    <div class="text-[10px] text-white/60 font-semibold uppercase tracking-wide">ສາຂາ</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 text-center min-w-[76px] border border-white/20">
                    <div class="text-2xl font-bold">{{ count($curriculumsList) }}</div>
                    <div class="text-[10px] text-white/60 font-semibold uppercase tracking-wide">ຫຼັກສູດ</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 text-center min-w-[76px] border border-white/20">
                    <div class="text-2xl font-bold">{{ count($subjectsList) }}</div>
                    <div class="text-[10px] text-white/60 font-semibold uppercase tracking-wide">ວິຊາຮຽນ</div>
                </div>
                <button wire:click="openCreateModal('{{ substr($activeTab, 0, -1) }}')"
                        class="bg-white text-primary px-5 py-3 rounded-xl font-bold flex items-center gap-2 hover:bg-white/90 active:scale-[0.98] transition-all shadow-lg ml-1">
                    <span class="material-symbols-outlined text-xl">add_circle</span>
                    <span class="text-sm">
                        @if($activeTab === 'majors') ເພີ່ມສາຂາ
                        @elseif($activeTab === 'curriculums') ເພີ່ມຫຼັກສູດ
                        @else ເພີ່ມວິຊາ
                        @endif
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Alert -->
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl text-sm flex items-center gap-3 shadow-sm">
            <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-emerald-600 text-lg">check_circle</span>
            </div>
            <span class="font-semibold text-emerald-800">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Main Card -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl overflow-hidden shadow-sm">

        <!-- Pill Tabs -->
        <div class="flex gap-1 p-3 bg-surface-container-low border-b border-outline-variant">
            <button wire:click="switchTab('majors')"
                    class="flex-1 md:flex-none px-5 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2
                    {{ $activeTab === 'majors' ? 'bg-white text-primary shadow-sm border border-outline-variant/50' : 'text-outline hover:text-primary hover:bg-white/50' }}">
                <span class="material-symbols-outlined text-xl">school</span>
                <span>ສາຂາວິຊາ</span>
                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full {{ $activeTab === 'majors' ? 'bg-primary text-white' : 'bg-outline-variant/40 text-outline' }}">{{ count($majorsList) }}</span>
            </button>
            <button wire:click="switchTab('curriculums')"
                    class="flex-1 md:flex-none px-5 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2
                    {{ $activeTab === 'curriculums' ? 'bg-white text-primary shadow-sm border border-outline-variant/50' : 'text-outline hover:text-primary hover:bg-white/50' }}">
                <span class="material-symbols-outlined text-xl">auto_stories</span>
                <span>ຫຼັກສູດ</span>
                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full {{ $activeTab === 'curriculums' ? 'bg-primary text-white' : 'bg-outline-variant/40 text-outline' }}">{{ count($curriculumsList) }}</span>
            </button>
            <button wire:click="switchTab('subjects')"
                    class="flex-1 md:flex-none px-5 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2
                    {{ $activeTab === 'subjects' ? 'bg-white text-primary shadow-sm border border-outline-variant/50' : 'text-outline hover:text-primary hover:bg-white/50' }}">
                <span class="material-symbols-outlined text-xl">library_books</span>
                <span>ວິຊາຮຽນ</span>
                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full {{ $activeTab === 'subjects' ? 'bg-primary text-white' : 'bg-outline-variant/40 text-outline' }}">{{ count($subjectsList) }}</span>
            </button>
        </div>

        <!-- Tab 1: Majors -->
        @if($activeTab === 'majors')
            <div class="p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="relative flex-1 max-w-sm">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline" style="font-size: 18px;">search</span>
                        <input wire:model.live.debounce.300ms="searchMajor" type="text"
                               class="w-full pl-10 pr-9 py-2.5 bg-background border border-outline-variant rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors"
                               placeholder="ຄົ້ນຫາສາຂາ..."/>
                        @if($searchMajor)
                            <button wire:click="$set('searchMajor', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-primary">
                                <span class="material-symbols-outlined" style="font-size: 16px;">close</span>
                            </button>
                        @endif
                    </div>
                    @if($searchMajor)
                        <p class="text-xs text-outline">ພົບ <span class="font-bold text-primary">{{ count($majorsList) }}</span> ລາຍການ</p>
                    @endif
                </div>
                <div class="overflow-x-auto rounded-xl border border-outline-variant">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low border-b border-outline-variant">
                                <th class="px-5 py-3.5 text-xs font-bold text-outline uppercase tracking-wider w-36">ລະຫັດ</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-outline uppercase tracking-wider">ຊື່ສາຂາວິຊາ</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-outline uppercase tracking-wider">ລາຍລະອຽດ</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-outline uppercase tracking-wider w-36 text-center">ສະຖານະ</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-outline uppercase tracking-wider w-28 text-right">ຈັດການ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/50 text-sm">
                            @forelse($majorsList as $major)
                                <tr class="hover:bg-primary/5 transition-colors">
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 bg-primary/10 text-primary text-xs font-bold rounded-lg font-mono">{{ $major->code }}</span>
                                    </td>
                                    <td class="px-5 py-4 font-semibold">{{ $major->name }}</td>
                                    <td class="px-5 py-4 text-xs text-outline max-w-xs truncate">{{ $major->description ?? '—' }}</td>
                                    <td class="px-5 py-4 text-center">
                                        @if($major->status === 'active')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-full border border-emerald-200">
                                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>ເປີດໃຊ້ງານ
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-surface-container text-outline text-[10px] font-bold rounded-full border border-outline-variant">
                                                <span class="w-1.5 h-1.5 bg-outline/40 rounded-full"></span>ປິດໃຊ້ງານ
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <button wire:click="openEditModal('major', {{ $major->id }})" class="p-2 text-outline hover:text-primary rounded-lg hover:bg-primary/10 transition-colors" title="ແກ້ໄຂ">
                                                <span class="material-symbols-outlined text-base">edit</span>
                                            </button>
                                            <button wire:click="deleteItem('major', {{ $major->id }})" wire:confirm="ທ່ານແນ່ໃຈບໍ່ວ່າຕ້ອງການລຶບສາຂາວິຊານີ້?"
                                                    class="p-2 text-outline hover:text-error rounded-lg hover:bg-error/10 transition-colors" title="ລຶບ">
                                                <span class="material-symbols-outlined text-base">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-14 h-14 rounded-2xl bg-surface-container flex items-center justify-center">
                                                <span class="material-symbols-outlined text-outline text-3xl">school</span>
                                            </div>
                                            <p class="text-sm font-semibold text-outline">ຍັງບໍ່ມີຂໍ້ມູນສາຂາວິຊາ</p>
                                            <p class="text-xs text-outline/60">ກົດ "ເພີ່ມສາຂາ" ເພື່ອສ້າງໃໝ່</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Tab 2: Curriculums -->
        @if($activeTab === 'curriculums')
            <div class="p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="relative flex-1 max-w-sm">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline" style="font-size: 18px;">search</span>
                        <input wire:model.live.debounce.300ms="searchCurriculum" type="text"
                               class="w-full pl-10 pr-9 py-2.5 bg-background border border-outline-variant rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors"
                               placeholder="ຄົ້ນຫາຫຼັກສູດ..."/>
                        @if($searchCurriculum)
                            <button wire:click="$set('searchCurriculum', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-primary">
                                <span class="material-symbols-outlined" style="font-size: 16px;">close</span>
                            </button>
                        @endif
                    </div>
                    @if($searchCurriculum)
                        <p class="text-xs text-outline">ພົບ <span class="font-bold text-primary">{{ count($curriculumsList) }}</span> ລາຍການ</p>
                    @endif
                </div>
                <div class="overflow-x-auto rounded-xl border border-outline-variant">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low border-b border-outline-variant">
                                <th class="px-5 py-3.5 text-xs font-bold text-outline uppercase tracking-wider w-44">ລະຫັດຫຼັກສູດ</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-outline uppercase tracking-wider">ຊື່ຫຼັກສູດ</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-outline uppercase tracking-wider w-44">ສາຂາວິຊາ</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-outline uppercase tracking-wider w-28 text-center">ໜ່ວຍກິດ</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-outline uppercase tracking-wider w-24 text-center">ໄລຍະ</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-outline uppercase tracking-wider w-32 text-center">ສະຖານະ</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-outline uppercase tracking-wider w-28 text-right">ຈັດການ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/50 text-sm">
                            @forelse($curriculumsList as $curr)
                                <tr class="hover:bg-primary/5 transition-colors">
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 bg-primary/10 text-primary text-xs font-bold rounded-lg font-mono">{{ $curr->curriculum_code }}</span>
                                    </td>
                                    <td class="px-5 py-4 font-semibold">{{ $curr->curriculum_name }}</td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-outline bg-surface-container px-2 py-1 rounded-lg">
                                            <span class="material-symbols-outlined text-xs">school</span>{{ $curr->major->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="text-sm font-bold text-primary">{{ $curr->total_credits }}</span>
                                        <span class="text-[10px] text-outline block leading-none mt-0.5">ໜ່ວຍກິດ</span>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="text-sm font-bold">{{ $curr->duration_years }}</span>
                                        <span class="text-[10px] text-outline block leading-none mt-0.5">ປີ</span>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        @if($curr->status === 'active')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-full border border-emerald-200">
                                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>ເປີດໃຊ້ງານ
                                            </span>
                                        @elseif($curr->status === 'draft')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-700 text-[10px] font-bold rounded-full border border-amber-200">
                                                <span class="w-1.5 h-1.5 bg-amber-400 rounded-full"></span>ສະບັບຮ່າງ
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-surface-container text-outline text-[10px] font-bold rounded-full border border-outline-variant">
                                                <span class="w-1.5 h-1.5 bg-outline/40 rounded-full"></span>ປິດໃຊ້ງານ
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <button wire:click="openEditModal('curriculum', {{ $curr->id }})" class="p-2 text-outline hover:text-primary rounded-lg hover:bg-primary/10 transition-colors" title="ແກ້ໄຂ">
                                                <span class="material-symbols-outlined text-base">edit</span>
                                            </button>
                                            <button wire:click="deleteItem('curriculum', {{ $curr->id }})" wire:confirm="ທ່ານແນ່ໃຈບໍ່ວ່າຕ້ອງການລຶບຫຼັກສູດນີ້?"
                                                    class="p-2 text-outline hover:text-error rounded-lg hover:bg-error/10 transition-colors" title="ລຶບ">
                                                <span class="material-symbols-outlined text-base">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-14 h-14 rounded-2xl bg-surface-container flex items-center justify-center">
                                                <span class="material-symbols-outlined text-outline text-3xl">auto_stories</span>
                                            </div>
                                            <p class="text-sm font-semibold text-outline">ຍັງບໍ່ມີຂໍ້ມູນຫຼັກສູດ</p>
                                            <p class="text-xs text-outline/60">ກົດ "ເພີ່ມຫຼັກສູດ" ເພື່ອສ້າງໃໝ່</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Tab 3: Subjects -->
        @if($activeTab === 'subjects')
            <div class="p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="relative flex-1 max-w-sm">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline" style="font-size: 18px;">search</span>
                        <input wire:model.live.debounce.300ms="searchSubject" type="text"
                               class="w-full pl-10 pr-9 py-2.5 bg-background border border-outline-variant rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors"
                               placeholder="ຄົ້ນຫາວິຊາ..."/>
                        @if($searchSubject)
                            <button wire:click="$set('searchSubject', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-primary">
                                <span class="material-symbols-outlined" style="font-size: 16px;">close</span>
                            </button>
                        @endif
                    </div>
                    @if($searchSubject)
                        <p class="text-xs text-outline">ພົບ <span class="font-bold text-primary">{{ count($subjectsList) }}</span> ລາຍການ</p>
                    @endif
                </div>
                <div class="overflow-x-auto rounded-xl border border-outline-variant">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low border-b border-outline-variant">
                                <th class="px-5 py-3.5 text-xs font-bold text-outline uppercase tracking-wider w-36">ລະຫັດວິຊາ</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-outline uppercase tracking-wider">ຊື່ວິຊາ</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-outline uppercase tracking-wider w-20 text-center">ໜ່ວຍກິດ</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-outline uppercase tracking-wider w-28 text-center">T / P</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-outline uppercase tracking-wider w-40">ສາຂາວິຊາ</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-outline uppercase tracking-wider w-32 text-center">ປີ / ພາກ</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-outline uppercase tracking-wider w-32">ວິຊາຕ້ອງຜ່ານ</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-outline uppercase tracking-wider w-28 text-right">ຈັດການ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/50 text-sm">
                            @forelse($subjectsList as $subj)
                                <tr class="hover:bg-primary/5 transition-colors">
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 bg-primary/10 text-primary text-xs font-bold rounded-lg font-mono">{{ $subj->subject_code }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-semibold">{{ $subj->subject_name }}</span>
                                            @if($subj->subject_name_en)
                                                <span class="text-[10px] text-outline mt-0.5">{{ $subj->subject_name_en }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="text-sm font-bold text-primary">{{ $subj->credits }}</span>
                                    </td>
                                    <td class="px-5 py-4 text-center text-xs">
                                        <span class="font-semibold">{{ $subj->theory_hours }}</span>
                                        <span class="text-outline mx-0.5">/</span>
                                        <span class="font-semibold text-outline">{{ $subj->practical_hours }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-outline bg-surface-container px-2 py-1 rounded-lg">
                                            <span class="material-symbols-outlined text-xs">school</span>{{ $subj->major->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <div class="flex flex-col items-center gap-0.5">
                                            <span class="text-xs font-bold bg-primary/10 text-primary px-2 py-0.5 rounded-md">ປີ {{ $subj->year_level }}</span>
                                            <span class="text-[10px] text-outline">ພາກ {{ $subj->semester }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-xs">
                                        @if($subj->prerequisite)
                                            <span class="px-2.5 py-1 bg-amber-50 text-amber-700 font-bold border border-amber-200 rounded-lg font-mono" title="{{ $subj->prerequisite->subject_name }}">
                                                {{ $subj->prerequisite->subject_code }}
                                            </span>
                                        @else
                                            <span class="text-outline">—</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <button wire:click="openEditModal('subject', {{ $subj->id }})" class="p-2 text-outline hover:text-primary rounded-lg hover:bg-primary/10 transition-colors" title="ແກ້ໄຂ">
                                                <span class="material-symbols-outlined text-base">edit</span>
                                            </button>
                                            <button wire:click="deleteItem('subject', {{ $subj->id }})" wire:confirm="ທ່ານແນ່ໃຈບໍ່ວ່າຕ້ອງການລຶບວິຊາຮຽນນີ້?"
                                                    class="p-2 text-outline hover:text-error rounded-lg hover:bg-error/10 transition-colors" title="ລຶບ">
                                                <span class="material-symbols-outlined text-base">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-5 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-14 h-14 rounded-2xl bg-surface-container flex items-center justify-center">
                                                <span class="material-symbols-outlined text-outline text-3xl">library_books</span>
                                            </div>
                                            <p class="text-sm font-semibold text-outline">ຍັງບໍ່ມີຂໍ້ມູນວິຊາຮຽນ</p>
                                            <p class="text-xs text-outline/60">ກົດ "ເພີ່ມວິຊາ" ເພື່ອສ້າງໃໝ່</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-on-background/50 backdrop-blur-sm">
            <div class="w-full max-w-2xl bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

                <!-- Modal Header -->
                <div class="px-6 py-5 border-b border-outline-variant bg-surface-container-low flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary/15 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-primary text-xl">
                                @if($modalType === 'major') school
                                @elseif($modalType === 'curriculum') auto_stories
                                @else library_books
                                @endif
                            </span>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-outline uppercase tracking-wider">{{ $isEdit ? 'ແກ້ໄຂ' : 'ສ້າງໃໝ່' }}</p>
                            <h4 class="text-base font-bold text-primary">
                                @if($modalType === 'major') ສາຂາວິຊາ
                                @elseif($modalType === 'curriculum') ຫຼັກສູດ
                                @else ວິຊາຮຽນ
                                @endif
                            </h4>
                        </div>
                    </div>
                    <button wire:click="closeModal" class="w-9 h-9 flex items-center justify-center hover:bg-surface-container-high rounded-lg transition-colors text-outline hover:text-primary">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto flex-1">

                    @if($modalType === 'major')
                        <form wire:submit.prevent="saveMajor" class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-2">ຊື່ສາຂາວິຊາ <span class="text-error">*</span></label>
                                <input wire:model="major_name" type="text"
                                       class="w-full bg-background border border-outline-variant rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors"
                                       placeholder="ຊື່ສາຂາວິຊາເຕັມ..."/>
                                @error('major_name') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-2">ລະຫັດຫຍໍ້ <span class="text-error">*</span></label>
                                    <input wire:model="major_code" type="text"
                                           class="w-full bg-background border border-outline-variant rounded-xl px-4 py-3 text-sm font-mono focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors"
                                           placeholder="BLL, ENG..."/>
                                    @error('major_code') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-2">ສະຖານະ</label>
                                    <select wire:model="major_status" class="w-full bg-background border border-outline-variant rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                                        <option value="active">ເປີດໃຊ້ງານ</option>
                                        <option value="inactive">ປິດໃຊ້ງານ</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-2">ຄຳອະທິບາຍ</label>
                                <textarea wire:model="major_description" rows="3"
                                          class="w-full bg-background border border-outline-variant rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors resize-none"
                                          placeholder="ລາຍລະອຽດສາຂາວິຊາ..."></textarea>
                            </div>
                            <button type="submit" class="hidden"></button>
                        </form>
                    @endif

                    @if($modalType === 'curriculum')
                        <form wire:submit.prevent="saveCurriculum" class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-2">ຊື່ຫຼັກສູດ <span class="text-error">*</span></label>
                                <input wire:model="curr_name" type="text"
                                       class="w-full bg-background border border-outline-variant rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors"
                                       placeholder="ຊື່ຫຼັກສູດເຕັມ..."/>
                                @error('curr_name') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-2">ລະຫັດຫຼັກສູດ <span class="text-error">*</span></label>
                                    <input wire:model="curr_code" type="text"
                                           class="w-full bg-background border border-outline-variant rounded-xl px-4 py-3 text-sm font-mono focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors"
                                           placeholder="CURR-BLL-2025..."/>
                                    @error('curr_code') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-2">ສາຂາວິຊາ <span class="text-error">*</span></label>
                                    <select wire:model="curr_major_id" class="w-full bg-background border border-outline-variant rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                                        <option value="">ເລືອກສາຂາ...</option>
                                        @foreach($allMajors as $major)
                                            <option value="{{ $major->id }}">{{ $major->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('curr_major_id') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-2">ສົກຮຽນເລີ່ມ <span class="text-error">*</span></label>
                                    <select wire:model="curr_academic_year_id" class="w-full bg-background border border-outline-variant rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                                        <option value="">ເລືອກ...</option>
                                        @foreach($allYears as $year)
                                            <option value="{{ $year->id }}">{{ $year->year }}</option>
                                        @endforeach
                                    </select>
                                    @error('curr_academic_year_id') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-2">ໜ່ວຍກິດລວມ <span class="text-error">*</span></label>
                                    <input wire:model="curr_total_credits" type="number"
                                           class="w-full bg-background border border-outline-variant rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors"/>
                                    @error('curr_total_credits') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-2">ສະຖານະ</label>
                                    <select wire:model="curr_status" class="w-full bg-background border border-outline-variant rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                                        <option value="active">ເປີດໃຊ້ງານ</option>
                                        <option value="inactive">ປິດໃຊ້ງານ</option>
                                        <option value="draft">ສະບັບຮ່າງ</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-2">ເກຣດຂັ້ນຕ່ຳ (Min GPA)</label>
                                    <input wire:model="curr_minimum_gpa" type="number" step="0.01"
                                           class="w-full bg-background border border-outline-variant rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors"/>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-2">ໄລຍະສຶກສາ (ປີ)</label>
                                    <input wire:model="curr_duration_years" type="number"
                                           class="w-full bg-background border border-outline-variant rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors"/>
                                </div>
                            </div>
                            <button type="submit" class="hidden"></button>
                        </form>
                    @endif

                    @if($modalType === 'subject')
                        <form wire:submit.prevent="saveSubject" class="space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-2">ຊື່ວິຊາ (ລາວ) <span class="text-error">*</span></label>
                                    <input wire:model="subj_name" type="text"
                                           class="w-full bg-background border border-outline-variant rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors"
                                           placeholder="ຊື່ວິຊາ..."/>
                                    @error('subj_name') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-2">ລະຫັດວິຊາ <span class="text-error">*</span></label>
                                    <input wire:model="subj_code" type="text"
                                           class="w-full bg-background border border-outline-variant rounded-xl px-4 py-3 text-sm font-mono focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors"
                                           placeholder="BLL-101..."/>
                                    @error('subj_code') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-2">ຊື່ວິຊາ (English)</label>
                                    <input wire:model="subj_name_en" type="text"
                                           class="w-full bg-background border border-outline-variant rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors"
                                           placeholder="English name..."/>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-2">ສາຂາວິຊາ <span class="text-error">*</span></label>
                                    <select wire:model="subj_major_id" class="w-full bg-background border border-outline-variant rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                                        <option value="">ເລືອກສາຂາ...</option>
                                        @foreach($allMajors as $major)
                                            <option value="{{ $major->id }}">{{ $major->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('subj_major_id') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <!-- Credits & Hours group -->
                            <div class="p-4 bg-surface-container-low rounded-xl border border-outline-variant">
                                <p class="text-[10px] font-bold text-outline uppercase tracking-wider mb-3">ໜ່ວຍກິດ ແລະ ຊົ່ວໂມງ</p>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-on-surface mb-1.5">ໜ່ວຍກິດ <span class="text-error">*</span></label>
                                        <input wire:model="subj_credits" type="number"
                                               class="w-full bg-background border border-outline-variant rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors"/>
                                        @error('subj_credits') <span class="text-xs text-error">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-on-surface mb-1.5">ທິດສະດີ (ຊມ.)</label>
                                        <input wire:model="subj_theory_hours" type="number"
                                               class="w-full bg-background border border-outline-variant rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors"/>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-on-surface mb-1.5">ປະຕິບັດ (ຊມ.)</label>
                                        <input wire:model="subj_practical_hours" type="number"
                                               class="w-full bg-background border border-outline-variant rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors"/>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-on-surface mb-1.5">ສະຖານະ</label>
                                        <select wire:model="subj_status" class="w-full bg-background border border-outline-variant rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                                            <option value="active">ເປີດ</option>
                                            <option value="inactive">ປິດ</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-2">ຊັ້ນປີຮຽນ <span class="text-error">*</span></label>
                                    <select wire:model="subj_year_level" class="w-full bg-background border border-outline-variant rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                                        <option value="1">ຊັ້ນປີ 1</option>
                                        <option value="2">ຊັ້ນປີ 2</option>
                                        <option value="3">ຊັ້ນປີ 3</option>
                                        <option value="4">ຊັ້ນປີ 4</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-2">ພາກຮຽນ <span class="text-error">*</span></label>
                                    <select wire:model="subj_semester" class="w-full bg-background border border-outline-variant rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                                        @for($i=1; $i<=8; $i++)
                                            <option value="{{ $i }}">ພາກຮຽນ {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-2">ວິຊາຕ້ອງຜ່ານກ່ອນ</label>
                                    <select wire:model="subj_prerequisite_id" class="w-full bg-background border border-outline-variant rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                                        <option value="">ບໍ່ມີ</option>
                                        @foreach($allSubjects as $s)
                                            @if($itemId !== $s->id)
                                                <option value="{{ $s->id }}">{{ $s->subject_code }} — {{ $s->subject_name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-2">ຄຳອະທິບາຍ</label>
                                <textarea wire:model="subj_description" rows="2"
                                          class="w-full bg-background border border-outline-variant rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors resize-none"
                                          placeholder="ລາຍລະອຽດ..."></textarea>
                            </div>
                            <button type="submit" class="hidden"></button>
                        </form>
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 border-t border-outline-variant bg-surface-container-low flex justify-end gap-3">
                    <button wire:click="closeModal"
                            class="px-5 py-2.5 border border-outline-variant hover:bg-surface-container-high rounded-xl text-sm font-semibold transition-all">
                        ຍົກເລີກ
                    </button>
                    <button wire:click="@if($modalType==='major') saveMajor @elseif($modalType==='curriculum') saveCurriculum @else saveSubject @endif"
                            class="px-6 py-2.5 bg-primary hover:bg-primary/90 text-on-primary rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">{{ $isEdit ? 'save' : 'add_circle' }}</span>
                        {{ $isEdit ? 'ບັນທຶກການແກ້ໄຂ' : 'ເພີ່ມຂໍ້ມູນ' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
