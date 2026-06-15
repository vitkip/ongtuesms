<div>
    @section('title', 'ຈັດການຜູ້ໃຊ້ລະບົບ')

    <!-- Page Header Banner -->
    <div class="relative overflow-hidden rounded-2xl text-on-primary p-8 mb-8 shadow-lg"
         style="background: linear-gradient(135deg, #00327d 0%, #0047ab 55%, #002f6c 100%);">
        <!-- Gold left accent stripe -->
        <div class="absolute left-0 top-0 h-full w-1.5 bg-secondary-container rounded-l-2xl"></div>
        
        <!-- Subtle grid pattern -->
        <div class="absolute inset-0 opacity-[0.04]"
             style="background-image: repeating-linear-gradient(0deg, #fff 0, #fff 1px, transparent 0, transparent 32px), repeating-linear-gradient(90deg, #fff 0, #fff 1px, transparent 0, transparent 32px);"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="pl-4">
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-secondary-container text-[18px]">manage_accounts</span>
                    <span class="text-secondary-container text-[10px] font-bold uppercase tracking-widest">ລະບົບບໍລິຫານບຸກຄະລາກອນ</span>
                </div>
                <h2 class="text-3xl font-bold text-white mb-2">ຈັດການຜູ້ໃຊ້ລະບົບ (ອາຈານ)</h2>
                <p class="text-sm text-white/65 max-w-2xl">
                    ເພີ່ມ, ແກ້ໄຂ, ລະງັບ ແລະ ກຳນົດສິດການເຂົ້າໃຊ້ງານ ລວມທັງວິຊາຮຽນທີ່ອາຈານຮັບຜິດຊອບ
                </p>
            </div>
            
            <button wire:click="openCreate"
                    class="shrink-0 bg-secondary-container text-on-background px-6 py-3 rounded-xl font-bold flex items-center gap-2 hover:brightness-105 active:scale-[0.98] transition-all shadow-lg border border-white/10">
                <span class="material-symbols-outlined text-[20px]">person_add</span>
                <span>ເພີ່ມຜູ້ໃຊ້ໃໝ່</span>
            </button>
        </div>

        <!-- Decorative Watermark -->
        <div class="absolute right-4 top-0 h-full flex items-center opacity-[0.05] pointer-events-none">
            <span class="material-symbols-outlined text-white" style="font-size: 200px; font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 48;">manage_accounts</span>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session()->has('message'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-xl text-sm flex items-center gap-3 shadow-sm transition-all duration-300">
            <span class="material-symbols-outlined text-emerald-600 shrink-0" style="font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;">check_circle</span>
            <span class="font-semibold">{{ session('message') }}</span>
        </div>
    @endif
    @if(session()->has('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-error text-red-800 rounded-r-xl text-sm flex items-center gap-3 shadow-sm transition-all duration-300">
            <span class="material-symbols-outlined text-error shrink-0" style="font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;">error</span>
            <span class="font-semibold">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Search Section -->
    <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant p-5 mb-6">
        <div class="relative max-w-md">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">search</span>
            <input wire:model.live.debounce.300ms="search"
                   type="text"
                   placeholder="ຄົ້ນຫາດ້ວຍ ຊື່ເຕັມ, Username ຫຼື ອີເມລ..."
                   class="w-full pl-9 pr-4 py-2.5 bg-background border border-outline-variant rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" />
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant text-[10px] font-bold text-primary uppercase tracking-widest">
                        <th class="px-6 py-4 w-16 text-center">#</th>
                        <th class="px-6 py-4">ຊື່ເຕັມ</th>
                        <th class="px-6 py-4 w-40">Username</th>
                        <th class="px-6 py-4 w-52">ອີເມລ</th>
                        <th class="px-6 py-4 w-32 text-center">ສິດເຂົ້າໃຊ້ງານ</th>
                        <th class="px-6 py-4 w-64">ວິຊາທີ່ສອນ</th>
                        <th class="px-6 py-4 w-28 text-center">ສະຖານະ</th>
                        <th class="px-6 py-4 w-40 text-center">ເຂົ້າລະບົບລ່າສຸດ</th>
                        <th class="px-6 py-4 text-center w-32">ຈັດການ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/60 text-sm">
                    @forelse($users as $index => $user)
                        <tr class="hover:bg-primary/[0.015] transition-colors group">
                            <!-- Index -->
                            <td class="px-6 py-4 text-center font-bold text-outline">{{ $users->firstItem() + $index }}</td>
                            
                            <!-- Full Name -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs shrink-0 border border-primary/20 shadow-xs">
                                        {{ mb_substr($user->full_name ?? $user->username, 0, 1) }}
                                    </div>
                                    <span class="font-bold text-on-surface">{{ $user->full_name ?? '—' }}</span>
                                </div>
                            </td>

                            <!-- Username -->
                            <td class="px-6 py-4 font-mono font-bold text-primary text-xs">{{ $user->username }}</td>

                            <!-- Email -->
                            <td class="px-6 py-4 text-outline font-medium">{{ $user->email ?? '—' }}</td>

                            <!-- Role -->
                            <td class="px-6 py-4 text-center">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-secondary/15 text-secondary border border-secondary/25 shadow-xs">
                                        <span class="material-symbols-outlined text-[13px]">admin_panel_settings</span>
                                        ແອດມິນ
                                    </span>
                                @elseif($user->role === 'finance')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-xs">
                                        <span class="material-symbols-outlined text-[13px]">payments</span>
                                        ການເງິນ
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-primary/10 text-primary border border-primary/20 shadow-xs">
                                        <span class="material-symbols-outlined text-[13px]">person</span>
                                        ອາຈານ
                                    </span>
                                @endif
                            </td>

                            <!-- Subject Assignments -->
                            <td class="px-6 py-4">
                                @if($user->subjects->isEmpty())
                                    <span class="text-outline text-xs italic font-medium">ຍັງບໍ່ໄດ້ກຳນົດວິຊາ</span>
                                @else
                                    <div class="flex flex-wrap gap-1 max-w-[220px]">
                                        @foreach($user->subjects->take(3) as $subject)
                                            <span class="px-1.5 py-0.5 bg-primary/10 text-primary text-[10px] rounded font-bold border border-primary/15" title="{{ $subject->subject_name }}">{{ $subject->subject_code }}</span>
                                        @endforeach
                                        @if($user->subjects->count() > 3)
                                            <span class="px-1.5 py-0.5 bg-surface-container text-outline text-[10px] rounded font-bold border border-outline-variant/60">+{{ $user->subjects->count() - 3 }}</span>
                                        @endif
                                    </div>
                                @endif
                            </td>

                            <!-- Status Toggle Badge -->
                            <td class="px-6 py-4 text-center">
                                <button wire:click="toggleActive({{ $user->id }})"
                                        class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-full border transition-all active:scale-[0.96] cursor-pointer
                                        {{ $user->is_active 
                                            ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100/70 hover:border-emerald-300' 
                                            : 'bg-red-50 text-red-700 border-red-200 hover:bg-red-100/70 hover:border-red-300' }}">
                                    <span class="material-symbols-outlined text-[13px]">{{ $user->is_active ? 'check_circle' : 'cancel' }}</span>
                                    <span>{{ $user->is_active ? 'ໃຊ້ງານ' : 'ລະງັບ' }}</span>
                                </button>
                            </td>

                            <!-- Last Login -->
                            <td class="px-6 py-4 text-center text-outline text-xs font-semibold">
                                {{ $user->last_login ? $user->last_login->diffForHumans() : 'ຍັງບໍ່ເຄີຍເຂົ້າລະບົບ' }}
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-1">
                                    <button wire:click="openEdit({{ $user->id }})"
                                            class="p-2 text-outline hover:text-primary hover:bg-surface-container rounded-lg transition-colors"
                                            title="ແກ້ໄຂ">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    @if($user->id !== auth()->id())
                                        <button wire:click="confirmDelete({{ $user->id }})"
                                                class="p-2 text-outline hover:text-error hover:bg-surface-container rounded-lg transition-colors"
                                                title="ລົບຜູ້ໃຊ້">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <span class="material-symbols-outlined text-5xl text-outline/30">manage_accounts</span>
                                    <p class="text-sm font-bold text-outline">ບໍ່ມີຂໍ້ມູນຜູ້ໃຊ້ລະບົບ</p>
                                    <p class="text-xs text-outline/60">ຍັງບໍ່ມີຂໍ້ມູນຜູ້ໃຊ້ທີ່ກົງກັບເງື່ອນໄຂການຄົ້ນຫາ</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-6 py-4 bg-surface-container-low border-t border-outline-variant">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Create / Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-on-background/40 backdrop-blur-sm transition-all duration-300">
            <div class="w-full max-w-2xl bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                <!-- Modal Header -->
                <div class="px-6 py-5 border-b border-outline-variant bg-surface-container-low flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-[20px]">manage_accounts</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-primary">{{ $editingId ? 'ແກ້ໄຂຂໍ້ມູນຜູ້ໃຊ້' : 'ເພີ່ມຜູ້ໃຊ້ໃໝ່' }}</h4>
                            <p class="text-[10px] text-outline">{{ $editingId ? 'ແກ້ໄຂລາຍລະອຽດບັນຊີ ແລະ ສິດເຂົ້າໃຊ້ງານ' : 'ປ້ອນຂໍ້ມູນເພື່ອສ້າງບັນຊີຜູ້ໃຊ້ ແລະ ກຳນົດສິດເຂົ້າໃຊ້ລະບົບ' }}</p>
                        </div>
                    </div>
                    <button wire:click="$set('showModal', false)" class="p-1.5 hover:bg-surface-container-high rounded-lg transition-colors text-outline hover:text-primary">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-on-surface">ຊື່ເຕັມ *</label>
                            <input wire:model="full_name" type="text" placeholder="ຊື່ ແລະ ນາມສະກຸນ"
                                   class="w-full bg-background border border-outline-variant rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" />
                            @error('full_name') <p class="text-error text-xs font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Username -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-on-surface">Username *</label>
                            <input wire:model="username" type="text" placeholder="ຊື່ຜູ້ໃຊ້ (ພາສາອັງກິດ)"
                                   class="w-full bg-background border border-outline-variant rounded-xl px-4 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" />
                            @error('username') <p class="text-error text-xs font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Email -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-on-surface">ອີເມລ</label>
                            <input wire:model="email" type="email" placeholder="example@email.com"
                                   class="w-full bg-background border border-outline-variant rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" />
                            @error('email') <p class="text-error text-xs font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Role -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-on-surface">ສິດໃຊ້ງານ *</label>
                            <select wire:model.live="role"
                                    class="w-full bg-background border border-outline-variant rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                <option value="user">ອາຈານສອນ (Teacher)</option>
                                <option value="finance">ພະນັກງານການເງິນ (Finance)</option>
                                <option value="admin">ຜູ້ດູແລລະບົບ (Admin)</option>
                            </select>
                            @error('role') <p class="text-error text-xs font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Password -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-on-surface">
                                ລະຫັດຜ່ານ {{ $editingId ? '(ວ່າງໄວ້ = ບໍ່ປ່ຽນ)' : '*' }}
                            </label>
                            <input wire:model="password" type="password" placeholder="ກຳນົດລະຫັດຜ່ານ..."
                                   class="w-full bg-background border border-outline-variant rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" />
                            @error('password') <p class="text-error text-xs font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-on-surface">ຢືນຢັນລະຫັດຜ່ານ</label>
                            <input wire:model="password_confirmation" type="password" placeholder="ປ້ອນລະຫັດຜ່ານອີກຄັ້ງ..."
                                   class="w-full bg-background border border-outline-variant rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" />
                        </div>
                    </div>

                    <!-- Active Status Toggle Switch -->
                    <div class="p-4 border border-outline-variant/50 rounded-2xl bg-surface-container-low flex items-center gap-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input wire:model="is_active" type="checkbox" id="is_active" class="sr-only peer" value="1" {{ $is_active ? 'checked' : '' }}/>
                            <div class="w-11 h-6 bg-outline-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                        <label for="is_active" class="text-xs font-bold text-on-background cursor-pointer">ເປີດໃຊ້ງານບັນຊີຜູ້ໃຊ້ນີ້</label>
                    </div>

                    <!-- Subject Assignment (teacher only) -->
                    @if($role === 'user')
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <span class="text-[10px] font-bold text-primary/60 uppercase tracking-widest flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[15px]">menu_book</span>
                                ວິຊາທີ່ຮັບຜິດຊອບສອນ
                            </span>
                            <div class="h-px flex-1 bg-outline-variant/60"></div>
                        </div>
                        <p class="text-[10px] text-outline leading-normal">ກະລຸນາເລືອກວິຊາຮຽນທີ່ອາຈານຄົນນີ້ຮັບຜິດຊອບ ອາຈານຈະເຫັນສະເພາະວິຊາທີ່ກຳນົດໃຫ້ເທົ່ານັ້ນ.</p>
                        
                        <div class="border border-outline-variant/70 rounded-xl p-3 max-h-52 overflow-y-auto bg-background/50 space-y-1.5 custom-scrollbar">
                            @foreach($subjects as $subject)
                                <label class="flex items-center gap-3 px-3 py-2.5 rounded-xl border border-outline-variant/40 hover:border-primary/30 hover:bg-primary/[0.015] cursor-pointer transition-all">
                                    <input type="checkbox"
                                           wire:model="selectedSubjects"
                                           value="{{ $subject->id }}"
                                           class="w-4.5 h-4.5 rounded text-primary focus:ring-primary border-outline-variant cursor-pointer accent-primary" />
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-baseline gap-x-2">
                                            <span class="text-xs font-mono font-bold text-primary bg-primary/10 px-1.5 py-0.5 rounded leading-none">{{ $subject->subject_code }}</span>
                                            <span class="text-sm font-bold text-on-surface">{{ $subject->subject_name }}</span>
                                        </div>
                                        @if($subject->major)
                                            <p class="text-[10px] text-outline mt-0.5 font-semibold">ສາຂາ: {{ $subject->major->name }}</p>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                            @if($subjects->isEmpty())
                                <p class="text-center text-outline text-xs py-6">ຍັງບໍ່ມີວິຊາໃນລະບົບ</p>
                            @endif
                        </div>
                        @error('selectedSubjects') <p class="text-error text-xs font-semibold mt-1 block">{{ $message }}</p> @enderror
                    </div>
                    @elseif($role === 'finance')
                    <div class="p-4 bg-primary/5 border border-primary/10 rounded-2xl text-xs text-on-surface flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-[18px]">info</span>
                        <span>ພະນັກງານການເງິນ ຈະສາມາດເຂົ້າໃຊ້ໄດ້ສະເພາະ <strong class="text-primary">ລະບົບການເງິນ (Invoices)</strong> ເທົ່ານັ້ນ</span>
                    </div>
                    @else
                    <div class="p-4 bg-secondary-container/15 border border-secondary/20 rounded-2xl text-xs text-on-surface flex items-center gap-3">
                        <span class="material-symbols-outlined text-secondary text-[18px]">admin_panel_settings</span>
                        <span>ຜູ້ດູແລລະບົບ ສາມາດເຂົ້າໃຊ້ <strong class="text-secondary">ທຸກສ່ວນ</strong> ຂອງລະບົບ</span>
                    </div>
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end gap-3 px-6 py-4 border-t border-outline-variant bg-surface-container-low">
                    <button wire:click="$set('showModal', false)"
                            class="px-5 py-2.5 border border-outline-variant hover:bg-surface-container-high rounded-xl text-sm font-bold text-on-surface transition-all active:scale-[0.98]">
                        ຍົກເລີກ
                    </button>
                    <button wire:click="save" wire:loading.attr="disabled"
                            class="px-5 py-2.5 bg-primary hover:bg-primary-container text-on-primary rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all active:scale-[0.98] disabled:opacity-50 flex items-center gap-2">
                        <span wire:loading wire:target="save">
                            <span class="material-symbols-outlined animate-spin text-[16px]">progress_activity</span>
                        </span>
                        <span>{{ $editingId ? 'ບັນທຶກການແກ້ໄຂ' : 'ເພີ່ມຜູ້ໃຊ້' }}</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirm Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-on-background/40 backdrop-blur-sm transition-all duration-300">
            <div class="bg-surface-container-lowest rounded-2xl shadow-2xl border border-outline-variant w-full max-w-sm overflow-hidden">
                <div class="p-6 flex flex-col items-center text-center gap-4">
                    <div class="w-14 h-14 bg-red-50 text-error border border-red-100 rounded-full flex items-center justify-center shadow-xs">
                        <span class="material-symbols-outlined text-[32px]">person_remove</span>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-lg font-bold text-on-surface">ຢືນຢັນການລົບຜູ້ໃຊ້</h3>
                        <p class="text-xs text-outline leading-normal px-2">ທ່ານແນ່ໃຈບໍ່ທີ່ຈະລົບຜູ້ໃຊ້ຄົນນີ້ອອກຈາກລະບົບ? ຂໍ້ມູນບັນຊີ ແລະ ປະຫວັດທັງໝົດຈະຖືກລົບຖາວອນ.</p>
                    </div>
                </div>
                
                <div class="flex justify-center gap-3 px-6 py-4 border-t border-outline-variant bg-surface-container-low">
                    <button wire:click="$set('showDeleteModal', false)"
                            class="px-5 py-2.5 border border-outline-variant hover:bg-surface-container-high rounded-xl text-sm font-bold text-on-surface transition-all active:scale-[0.98] flex-1">
                        ຍົກເລີກ
                    </button>
                    <button wire:click="deleteUser"
                            class="px-5 py-2.5 bg-error text-on-error hover:opacity-95 rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all active:scale-[0.98] flex-1">
                        ລົບຜູ້ໃຊ້
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
