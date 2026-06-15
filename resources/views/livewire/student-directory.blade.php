<div>
    <!-- Header Banner -->
    <div class="relative overflow-hidden rounded-2xl text-on-primary p-8 mb-6 shadow-lg"
         style="background: linear-gradient(135deg, #00327d 0%, #0047ab 55%, #002f6c 100%);">
        <!-- Gold left accent stripe -->
        <div class="absolute left-0 top-0 h-full w-1.5 bg-secondary-container rounded-l-2xl"></div>
        
        <!-- Subtle grid pattern -->
        <div class="absolute inset-0 opacity-[0.04]"
             style="background-image: repeating-linear-gradient(0deg, #fff 0, #fff 1px, transparent 0, transparent 32px), repeating-linear-gradient(90deg, #fff 0, #fff 1px, transparent 0, transparent 32px);"></div>
        
        <!-- Dhammachakka watermark -->
        <svg class="absolute right-8 top-1/2 -translate-y-1/2 opacity-[0.06] select-none pointer-events-none hidden md:block text-white" width="160" height="160" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="4">
            <circle cx="50" cy="50" r="45" stroke-dasharray="2 2" stroke-width="2" />
            <circle cx="50" cy="50" r="40" stroke-width="5" />
            <circle cx="50" cy="50" r="10" stroke-width="3" />
            <path d="M50 10 L50 90 M10 50 L90 50 M21.7 21.7 L78.3 78.3 M21.7 78.3 L78.3 21.7" stroke-width="4" />
            <circle cx="50" cy="50" r="3" fill="currentColor"/>
        </svg>

        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="pl-4">
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-secondary-container text-[18px]">school</span>
                    <span class="text-secondary-container text-[10px] font-bold uppercase tracking-widest">ລະບົບທະບຽນນັກສຶກສາ</span>
                </div>
                <h2 class="text-3xl font-bold text-white mb-2">ລາຍຊື່ນັກສຶກສາທັງໝົດ</h2>
                <p class="text-sm text-white/65 max-w-2xl">
                    ຄົ້ນຫາ, ຈັດການຂໍ້ມູນ ແລະ ຕິດຕາມປະຫວັດນັກສຶກສາ ວິທະຍາໄລຄູສົງ ອົງຕື້
                </p>
            </div>
            
            <button wire:click="openCreateModal" 
                    class="shrink-0 bg-secondary-container text-on-background px-6 py-3 rounded-xl font-bold flex items-center gap-2 hover:brightness-105 active:scale-[0.98] transition-all shadow-lg border border-white/10">
                <span class="material-symbols-outlined text-[20px]">person_add</span>
                <span>ເພີ່ມນັກສຶກສາໃໝ່</span>
            </button>
        </div>
    </div>

    <!-- Alert Message -->
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-xl text-sm flex items-center gap-3 shadow-sm transition-all duration-300">
            <span class="material-symbols-outlined text-emerald-600 shrink-0" style="font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;">check_circle</span>
            <span class="font-semibold">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Statistics Panel -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card 1: Total Students -->
        <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant flex items-center justify-between shadow-sm hover:shadow-md transition-shadow">
            <div class="space-y-1">
                <span class="text-xs font-bold text-outline uppercase tracking-wider">ນັກສຶກສາທັງໝົດ</span>
                <h3 class="text-3xl font-black text-primary">{{ \App\Models\Student::count() }}</h3>
                <span class="text-[10px] text-outline/80">ລາຍຊື່ລົງທະບຽນທັງໝົດ</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">groups</span>
            </div>
        </div>

        <!-- Card 2: Monastic Students (Monks & Novices) -->
        <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant flex items-center justify-between shadow-sm hover:shadow-md transition-shadow">
            <div class="space-y-1">
                <span class="text-xs font-bold text-outline uppercase tracking-wider">ພຣະສາມະເນນ</span>
                <h3 class="text-3xl font-black text-secondary">{{ \App\Models\Student::whereIn('gender', ['ພຣະ', 'ສ.ນ'])->count() }}</h3>
                <span class="text-[10px] text-outline/80">ພຣະພິກຂຸ ແລະ ສາມະເນນ</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-secondary/15 flex items-center justify-center text-secondary">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">spa</span>
            </div>
        </div>

        <!-- Card 3: Lay Students -->
        <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant flex items-center justify-between shadow-sm hover:shadow-md transition-shadow">
            <div class="space-y-1">
                <span class="text-xs font-bold text-outline uppercase tracking-wider">ຄະລາວາດ (ຊາຍ-ຍິງ)</span>
                <h3 class="text-3xl font-black text-on-surface">{{ \App\Models\Student::whereIn('gender', ['ຊາຍ', 'ຍິງ'])->count() }}</h3>
                <span class="text-[10px] text-outline/80">ນັກສຶກສາທົ່ວໄປ</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center text-outline">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">person</span>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant flex flex-wrap items-end gap-4 mb-6 shadow-sm">
        <!-- Search -->
        <div class="flex-1 min-w-[240px]">
            <label class="block text-[10px] font-bold text-primary/60 uppercase tracking-widest mb-1.5">ຄົ້ນຫານັກສຶກສາ</label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-outline text-[18px] pointer-events-none">search</span>
                <input wire:model.live.debounce.300ms="search" 
                       type="text" 
                       class="w-full bg-background border border-outline-variant rounded-xl pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" 
                       placeholder="ຄົ້ນຫາດ້ວຍ ຊື່, ນາມສະກຸນ ຫຼື ລະຫັດ..."/>
            </div>
        </div>

        <!-- Major Filter -->
        <div class="w-56 min-w-[180px]">
            <label class="block text-[10px] font-bold text-primary/60 uppercase tracking-widest mb-1.5">ສາຂາວિຊາ (Major)</label>
            <select wire:model.live="filterMajor" 
                    class="w-full bg-background border border-outline-variant rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                <option value="">ທັງໝົດ</option>
                @foreach($majors as $major)
                    <option value="{{ $major->id }}">{{ $major->name }} ({{ $major->code }})</option>
                @endforeach
            </select>
        </div>

        <!-- Academic Year Filter -->
        <div class="w-44 min-w-[140px]">
            <label class="block text-[10px] font-bold text-primary/60 uppercase tracking-widest mb-1.5">ສົກຮຽນ</label>
            <select wire:model.live="filterYear" 
                    class="w-full bg-background border border-outline-variant rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                <option value="">ທັງໝົດ</option>
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}">{{ $year->year }}</option>
                @endforeach
            </select>
        </div>

        <!-- Gender Filter -->
        <div class="w-44 min-w-[140px]">
            <label class="block text-[10px] font-bold text-primary/60 uppercase tracking-widest mb-1.5">ເພດ (ບໍລິບົດສົງ)</label>
            <select wire:model.live="filterGender" 
                    class="w-full bg-background border border-outline-variant rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                <option value="">ທັງໝົດ</option>
                <option value="ພຣະ">ພຣະ</option>
                <option value="ສ.ນ">ສ.ນ</option>
                <option value="ຊາຍ">ຊາຍ (ຄະລາວາດ)</option>
                <option value="ຍິງ">ຍິງ (ຄະລາວາດ)</option>
                <option value="ອຶ່ນໆ">ອຶ່ນໆ</option>
            </select>
        </div>

        <!-- Reset Button -->
        <button wire:click="resetFilters" 
                class="bg-surface-container-high hover:bg-surface-container-highest text-primary p-2.5 rounded-xl border border-outline-variant transition-all flex items-center justify-center shrink-0 active:scale-[0.95]" 
                title="ລ້າງການກອງຂໍ້ມູນ">
            <span class="material-symbols-outlined text-[20px]">refresh</span>
        </button>
    </div>

    <!-- Data Table -->
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant text-[10px] font-bold text-primary uppercase tracking-widest">
                        <th class="px-6 py-4 w-40">ລະຫັດນັກສຶກສາ</th>
                        <th class="px-6 py-4">ຊື່ ແລະ ນາມສະກຸນ</th>
                        <th class="px-6 py-4 w-28 text-center">ເພດ</th>
                        <th class="px-6 py-4 w-60">ສາຂາວિຊາ</th>
                        <th class="px-6 py-4 w-32 text-center">ສົກຮຽນ</th>
                        <th class="px-6 py-4 w-48 text-center">ທີ່ຢູ່ປັດຈຸບັນ</th>
                        <th class="px-6 py-4 text-right w-36">ຈັດການ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/60 text-sm">
                    @forelse($students as $student)
                        <tr class="hover:bg-primary/[0.015] transition-colors group">
                            <!-- Student ID -->
                            <td class="px-6 py-4 font-mono font-bold text-primary">{{ $student->student_id ?? '-' }}</td>
                            
                            <!-- Name with Photo -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full overflow-hidden border border-outline-variant bg-surface-container flex items-center justify-center shrink-0 shadow-sm">
                                        @if($student->photo)
                                            <img class="w-full h-full object-cover" 
                                                 src="{{ Storage::url('students/' . $student->photo) }}" 
                                                 alt="{{ $student->full_name }}"/>
                                        @else
                                            <span class="material-symbols-outlined text-primary/60 text-xl">person</span>
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-on-surface text-sm">{{ $student->gendered_name }}</span>
                                        <span class="text-[10px] text-outline font-semibold leading-none mt-1">{{ $student->email ?? 'ບໍ່ມີອີເມລ' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Gender Badge -->
                            <td class="px-6 py-4 text-center">
                                @if(in_array($student->gender, ['ພຣະ', 'ສ.ນ']))
                                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 bg-secondary/10 text-secondary text-xs font-bold rounded-full border border-secondary/20">
                                        {{ $student->gender }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 bg-primary/10 text-primary text-xs font-bold rounded-full border border-primary/20">
                                        {{ $student->gender }}
                                    </span>
                                @endif
                            </td>

                            <!-- Major -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-on-surface/90">{{ $student->major->name ?? 'N/A' }}</span>
                                    <span class="text-[10px] text-outline font-semibold mt-0.5">ລະຫັດ: {{ $student->major->code ?? '-' }}</span>
                                </div>
                            </td>

                            <!-- Academic Year -->
                            <td class="px-6 py-4 text-center font-bold text-on-surface/80">{{ $student->academicYear->year ?? '-' }}</td>
                            
                            <!-- Address -->
                            <td class="px-6 py-4 text-center">
                                @if($student->village || $student->district)
                                    <span class="text-xs font-medium text-on-surface/70">{{ $student->village }}, {{ $student->district }}</span>
                                @else
                                    <span class="text-outline/65 text-xs">—</span>
                                @endif
                            </td>

                            <!-- Action buttons -->
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('students.transcript', $student->id) }}" 
                                       target="_blank"
                                       class="p-2 text-outline hover:text-secondary hover:bg-surface-container rounded-lg transition-colors flex items-center justify-center"
                                       title="ໃບສະຫຼຸບຄະແນນ (Transcript)">
                                        <span class="material-symbols-outlined text-[18px]">description</span>
                                    </a>
                                    <button wire:click="openEditModal({{ $student->id }})" 
                                            class="p-2 text-outline hover:text-primary hover:bg-surface-container rounded-lg transition-colors"
                                            title="ແກ້ໄຂ">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    <button wire:click="delete({{ $student->id }})" 
                                            wire:confirm="ທ່ານແນ່ໃຈບໍ່ວ່າຕ້ອງການລຶບຂໍ້ມູນນັກສຶກສາຄົນນີ້?"
                                            class="p-2 text-outline hover:text-error hover:bg-surface-container rounded-lg transition-colors"
                                            title="ລຶບ">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <span class="material-symbols-outlined text-5xl text-outline/30">person_search</span>
                                    <p class="text-sm font-bold text-outline">ບໍ່ມີຂໍ້ມູນນັກສຶກສາ</p>
                                    <p class="text-xs text-outline/60">ບໍ່ມີຂໍ້ມູນນັກສຶກສາທີ່ກົງກັບເງື່ອນໄຂການຄົ້ນຫາ</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-surface-container-low border-t border-outline-variant">
            {{ $students->links() }}
        </div>
    </div>

    <!-- Modal Form (Create & Edit) -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-on-background/40 backdrop-blur-sm transition-all duration-300">
            <div class="w-full max-w-3xl bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                <!-- Modal Header -->
                <div class="px-6 py-5 border-b border-outline-variant bg-surface-container-low flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-[20px]">person</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-primary">{{ $isEdit ? 'ແກ້ໄຂຂໍ້ມູນນັກສຶກສາ' : 'ເພີ່ມນັກສຶກສາໃໝ່' }}</h4>
                            <p class="text-[10px] text-outline">{{ $isEdit ? 'ແກ້ໄຂລາຍລະອຽດຂໍ້ມູນນັກສຶກສາໃນລະບົບ' : 'ກະລຸນາປ້ອນຂໍ້ມູນເພື່ອລົງທະບຽນນັກສຶກສາໃໝ່' }}</p>
                        </div>
                    </div>
                    <button wire:click="closeModal" class="p-1.5 hover:bg-surface-container-high rounded-lg transition-colors text-outline hover:text-primary">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <!-- Modal Body (Form) -->
                <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-6">
                    <form wire:submit.prevent="save" class="space-y-6">
                        
                        <!-- Section 1: ຂໍ້ມູນສ່ວນຕົວ -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <span class="text-[10px] font-bold text-primary/60 uppercase tracking-widest">ຂໍ້ມູນສ່ວນຕົວ</span>
                                <div class="h-px flex-1 bg-outline-variant/60"></div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- First Name -->
                                <div>
                                    <label class="block text-xs font-bold text-on-surface mb-2">ຊື່ (First Name) *</label>
                                    <input wire:model="first_name" type="text" class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="ປ້ອນຊື່ນັກສຶກສາ..."/>
                                    @error('first_name') <span class="text-xs text-error font-semibold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Last Name -->
                                <div>
                                    <label class="block text-xs font-bold text-on-surface mb-2">ນາມສະກຸນ (Last Name) *</label>
                                    <input wire:model="last_name" type="text" class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="ປ້ອນນາມສະກຸນ..."/>
                                    @error('last_name') <span class="text-xs text-error font-semibold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Gender -->
                                <div>
                                    <label class="block text-xs font-bold text-on-surface mb-2">ເພດ (ບໍລິບົດສົງ) *</label>
                                    <select wire:model="gender" class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                        <option value="ພຣະ">ພຣະ</option>
                                        <option value="ສ.ນ">ສ.ນ</option>
                                        <option value="ຊາຍ">ຊາຍ (ຄະລາວາດ)</option>
                                        <option value="ຍິງ">ຍິງ (ຄະລາວາດ)</option>
                                        <option value="ອຶ່ນໆ">ອຶ່ນໆ</option>
                                    </select>
                                    @error('gender') <span class="text-xs text-error font-semibold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- DOB -->
                                <div>
                                    <label class="block text-xs font-bold text-on-surface mb-2">ວັນເດືອນປີເກີດ (DOB) *</label>
                                    <input wire:model="dob" type="date" class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"/>
                                    @error('dob') <span class="text-xs text-error font-semibold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label class="block text-xs font-bold text-on-surface mb-2">ເບີໂທຕິດຕໍ່ (Phone)</label>
                                    <input wire:model="phone" type="text" class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="ເບີໂທ..."/>
                                    @error('phone') <span class="text-xs text-error font-semibold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: ຂໍ້ມູນການສຶກສາ -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <span class="text-[10px] font-bold text-primary/60 uppercase tracking-widest">...
                                <span class="text-[10px] font-bold text-primary/60 uppercase tracking-widest">ຂໍ້ມູນການສຶກສາ</span>
                                <div class="h-px flex-1 bg-outline-variant/60"></div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Major -->
                                <div>
                                    <label class="block text-xs font-bold text-on-surface mb-2">ສາຂາວິຊາ (Major) *</label>
                                    <select wire:model="major_id" class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                        <option value="">ເລືອກສາຂາ...</option>
                                        @foreach($majors as $major)
                                            <option value="{{ $major->id }}">{{ $major->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('major_id') <span class="text-xs text-error font-semibold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Academic Year -->
                                <div>
                                    <label class="block text-xs font-bold text-on-surface mb-2">ສົກຮຽນທີ່ລົງທະບຽນ *</label>
                                    <select wire:model="academic_year_id" class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                        <option value="">ເລືອກສົກຮຽນ...</option>
                                        @foreach($academicYears as $year)
                                            <option value="{{ $year->id }}">{{ $year->year }}</option>
                                        @endforeach
                                    </select>
                                    @error('academic_year_id') <span class="text-xs text-error font-semibold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Previous School -->
                            <div>
                                <label class="block text-xs font-bold text-on-surface mb-2">ໂຮງຮຽນເດີມ (Previous School)</label>
                                <input wire:model="previous_school" type="text" class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="ໂຮງຮຽນເກົ່າ / ມສ..."/>
                            </div>
                        </div>

                        <!-- Section 3: ທີ່ຢູ່ ແລະ ບ່ອນພັກເຊົາ -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <span class="text-[10px] font-bold text-primary/60 uppercase tracking-widest">ທີ່ຢູ່ ແລະ ບ່ອນພັກເຊົາ</span>
                                <div class="h-px flex-1 bg-outline-variant/60"></div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Village -->
                                <div>
                                    <label class="block text-xs font-bold text-on-surface mb-2">ບ້ານ (Village)</label>
                                    <input wire:model="village" type="text" class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="ບ້ານ..."/>
                                </div>

                                <!-- District -->
                                <div>
                                    <label class="block text-xs font-bold text-on-surface mb-2">ເມືອງ (District)</label>
                                    <input wire:model="district" type="text" class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="ເມືອງ..."/>
                                </div>

                                <!-- Province -->
                                <div>
                                    <label class="block text-xs font-bold text-on-surface mb-2">ແຂວງ (Province)</label>
                                    <input wire:model="province" type="text" class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="ແຂວງ..."/>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Email -->
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-on-surface mb-2">ອີເມລ (Email)</label>
                                    <input wire:model="email" type="email" class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="ອີເມລ..."/>
                                    @error('email') <span class="text-xs text-error font-semibold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Accommodation Type -->
                                <div>
                                    <label class="block text-xs font-bold text-on-surface mb-2">ການຈັດຫາວັດ (Accommodation)</label>
                                    <select wire:model="accommodation_type" class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                        <option value="ມີວັດຢູ່ແລ້ວ">ມີວັດຢູ່ແລ້ວ</option>
                                        <option value="ຫາວັດໃຫ້">ຫາວັດໃຫ້</option>
                                    </select>
                                    @error('accommodation_type') <span class="text-xs text-error font-semibold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: ຮູບພາບນັກສຶກສາ -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <span class="text-[10px] font-bold text-primary/60 uppercase tracking-widest">ຮູບພາບນັກສຶກສາ</span>
                                <div class="h-px flex-1 bg-outline-variant/60"></div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Photo Upload -->
                                <div>
                                    <label class="block text-xs font-bold text-on-surface mb-2">ຮູບຖ່າຍນັກສຶກສາ (Photo)</label>
                                    <input wire:model="photo" type="file" class="w-full text-xs text-outline file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/15 transition-all"/>
                                    @error('photo') <span class="text-xs text-error font-semibold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Photo Preview -->
                                <div class="flex items-center gap-3">
                                    @if ($photo)
                                        <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-primary/20 bg-surface-container shadow-sm flex items-center justify-center shrink-0">
                                            <img class="w-full h-full object-cover" src="{{ $photo->temporaryUrl() }}"/>
                                        </div>
                                        <div>
                                            <span class="text-[11px] font-bold text-primary block">ຮູບພາບທີ່ເລືອກໃໝ່</span>
                                            <span class="text-[9px] text-outline font-semibold">ກຽມອັບໂຫຼດ...</span>
                                        </div>
                                    @elseif ($existingPhoto)
                                        <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-primary/10 bg-surface-container shadow-sm flex items-center justify-center shrink-0">
                                            <img class="w-full h-full object-cover" src="{{ Storage::url('students/' . $existingPhoto) }}"/>
                                        </div>
                                        <div>
                                            <span class="text-[11px] font-bold text-outline block">ຮູບພາບປະຈຸບັນ</span>
                                            <span class="text-[9px] text-outline/70 font-semibold">ບັນທຶກແລ້ວ</span>
                                        </div>
                                    @else
                                        <div class="w-16 h-16 rounded-full border border-dashed border-outline-variant bg-surface-container flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-outline/40 text-2xl">image</span>
                                        </div>
                                        <div>
                                            <span class="text-[11px] font-bold text-outline/65 block">ບໍ່ມີຮູບພາບ</span>
                                            <span class="text-[9px] text-outline/50 font-semibold">ແນະນຳໃຫ້ອັບໂຫຼດ</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Hidden submit for enter-key press -->
                        <button type="submit" class="hidden"></button>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-5 border-t border-outline-variant bg-surface-container-low flex justify-end gap-3">
                    <button wire:click="closeModal" 
                            class="px-5 py-2.5 border border-outline-variant hover:bg-surface-container-high rounded-xl text-sm text-on-surface font-bold transition-all active:scale-[0.98]">
                        ຍົກເລີກ
                    </button>
                    <button wire:click="save" 
                            class="px-5 py-2.5 bg-primary hover:bg-primary-container text-on-primary rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all active:scale-[0.98]">
                        {{ $isEdit ? 'ບັນທຶກການແກ້ໄຂ' : 'ເພີ່ມນັກສຶກສາ' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
