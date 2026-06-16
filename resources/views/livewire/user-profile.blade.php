<div class="max-w-2xl mx-auto">

    <!-- Page Header -->
    <div class="mb-8">
        <p class="text-[10px] uppercase tracking-[0.18em] font-bold text-secondary mb-2">ບັນຊີຂອງຂ້ອຍ</p>
        <h2 class="text-[1.75rem] font-bold text-on-background leading-tight">ໂປຣໄຟລ໌ຂ້ອຍ</h2>
        <p class="text-on-surface-variant text-sm mt-1">ແກ້ໄຂຂໍ້ມູນສ່ວນຕົວ ແລະ ລະຫັດຜ່ານຂອງທ່ານ</p>
    </div>

    <!-- User Card -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl mb-6 overflow-hidden">
        <div class="h-[3px] w-full bg-primary"></div>
        <div class="p-6 flex items-center gap-5">
            <div class="w-16 h-16 rounded-full bg-primary flex items-center justify-center flex-shrink-0">
                <span class="text-on-primary text-2xl font-bold">
                    {{ mb_strtoupper(mb_substr(Auth::user()->full_name ?? Auth::user()->username, 0, 1)) }}
                </span>
            </div>
            <div>
                <h3 class="text-lg font-bold text-on-surface">{{ Auth::user()->full_name ?? Auth::user()->username }}</h3>
                <p class="text-sm text-outline mt-0.5">{{ Auth::user()->username }}</p>
                <span class="inline-flex items-center gap-1.5 mt-2 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                    @if(Auth::user()->role === 'admin') bg-primary/10 text-primary
                    @elseif(Auth::user()->role === 'finance') bg-amber-100 text-amber-700
                    @else bg-green-50 text-green-700 @endif">
                    <span class="material-symbols-outlined" style="font-size:12px; font-variation-settings: 'FILL' 1;">
                        @if(Auth::user()->role === 'admin') shield_person
                        @elseif(Auth::user()->role === 'finance') payments
                        @else school @endif
                    </span>
                    {{ Auth::user()->role === 'admin' ? 'ຜູ້ດູແລລະບົບ' : (Auth::user()->role === 'finance' ? 'ການເງິນ' : 'ອາຈານສອນ') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex gap-1 mb-5 bg-surface-container-low p-1 rounded-xl">
        <button wire:click="$set('activeTab', 'info')"
                class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-lg text-sm font-semibold transition-all
                    {{ $activeTab === 'info' ? 'bg-surface-container-lowest text-primary shadow-sm' : 'text-outline hover:text-on-surface' }}">
            <span class="material-symbols-outlined" style="font-size:18px;">person</span>
            ຂໍ້ມູນສ່ວນຕົວ
        </button>
        <button wire:click="$set('activeTab', 'password')"
                class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-lg text-sm font-semibold transition-all
                    {{ $activeTab === 'password' ? 'bg-surface-container-lowest text-primary shadow-sm' : 'text-outline hover:text-on-surface' }}">
            <span class="material-symbols-outlined" style="font-size:18px;">lock</span>
            ປ່ຽນລະຫັດຜ່ານ
        </button>
    </div>

    <!-- Tab: Personal Info -->
    @if($activeTab === 'info')
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-outline-variant">
            <h4 class="text-base font-bold text-on-surface">ຂໍ້ມູນສ່ວນຕົວ</h4>
            <p class="text-xs text-outline mt-0.5">ແກ້ໄຂຊື່ເຕັມ ແລະ ອີເມລຂອງທ່ານ</p>
        </div>
        <div class="p-6">
            @if(session('info_success'))
                <div class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm font-semibold">
                    <span class="material-symbols-outlined" style="font-size:18px; font-variation-settings: 'FILL' 1;">check_circle</span>
                    {{ session('info_success') }}
                </div>
            @endif

            <form wire:submit.prevent="saveInfo" class="space-y-5">
                <!-- Username (readonly) -->
                <div>
                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1.5">ຊື່ຜູ້ໃຊ້</label>
                    <div class="flex items-center gap-2 px-4 py-2.5 bg-surface-container rounded-lg border border-outline-variant text-sm text-outline cursor-not-allowed">
                        <span class="material-symbols-outlined text-outline" style="font-size:16px;">badge</span>
                        {{ Auth::user()->username }}
                        <span class="ml-auto text-[10px] bg-surface-container-high px-2 py-0.5 rounded-full font-semibold">ບໍ່ສາມາດປ່ຽນໄດ້</span>
                    </div>
                </div>

                <!-- Full Name -->
                <div>
                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1.5" for="full_name">ຊື່ເຕັມ <span class="text-error">*</span></label>
                    <input id="full_name"
                           wire:model="full_name"
                           type="text"
                           placeholder="ປ້ອນຊື່ເຕັມ..."
                           class="w-full px-4 py-2.5 bg-surface-container border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary
                               {{ $errors->has('full_name') ? 'border-error bg-error-container/10' : 'border-outline-variant' }}"/>
                    @error('full_name')
                        <p class="mt-1.5 text-xs text-error flex items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size:14px;">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1.5" for="email">ອີເມລ</label>
                    <input id="email"
                           wire:model="email"
                           type="email"
                           placeholder="example@email.com"
                           class="w-full px-4 py-2.5 bg-surface-container border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary
                               {{ $errors->has('email') ? 'border-error bg-error-container/10' : 'border-outline-variant' }}"/>
                    @error('email')
                        <p class="mt-1.5 text-xs text-error flex items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size:14px;">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-on-primary text-sm font-bold rounded-lg hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary/50">
                        <span class="material-symbols-outlined" style="font-size:18px;">save</span>
                        ບັນທຶກຂໍ້ມູນ
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Tab: Change Password -->
    @if($activeTab === 'password')
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-outline-variant">
            <h4 class="text-base font-bold text-on-surface">ປ່ຽນລະຫັດຜ່ານ</h4>
            <p class="text-xs text-outline mt-0.5">ກະລຸນາໃຊ້ລະຫັດຜ່ານທີ່ປອດໄພ ຢ່າງໜ້ອຍ 6 ຕົວອັກສອນ</p>
        </div>
        <div class="p-6">
            @if(session('password_success'))
                <div class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm font-semibold">
                    <span class="material-symbols-outlined" style="font-size:18px; font-variation-settings: 'FILL' 1;">check_circle</span>
                    {{ session('password_success') }}
                </div>
            @endif

            <form wire:submit.prevent="savePassword" class="space-y-5">
                <!-- Current Password -->
                <div>
                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1.5" for="current_password">ລະຫັດຜ່ານປັດຈຸບັນ <span class="text-error">*</span></label>
                    <input id="current_password"
                           wire:model="current_password"
                           type="password"
                           placeholder="••••••••"
                           class="w-full px-4 py-2.5 bg-surface-container border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary
                               {{ $errors->has('current_password') ? 'border-error bg-error-container/10' : 'border-outline-variant' }}"/>
                    @error('current_password')
                        <p class="mt-1.5 text-xs text-error flex items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size:14px;">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1.5" for="new_password">ລະຫັດຜ່ານໃໝ່ <span class="text-error">*</span></label>
                    <input id="new_password"
                           wire:model="new_password"
                           type="password"
                           placeholder="••••••••"
                           class="w-full px-4 py-2.5 bg-surface-container border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary
                               {{ $errors->has('new_password') ? 'border-error bg-error-container/10' : 'border-outline-variant' }}"/>
                    @error('new_password')
                        <p class="mt-1.5 text-xs text-error flex items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size:14px;">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Confirm New Password -->
                <div>
                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1.5" for="new_password_confirmation">ຢືນຢັນລະຫັດຜ່ານໃໝ່ <span class="text-error">*</span></label>
                    <input id="new_password_confirmation"
                           wire:model="new_password_confirmation"
                           type="password"
                           placeholder="••••••••"
                           class="w-full px-4 py-2.5 bg-surface-container border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary
                               {{ $errors->has('new_password_confirmation') ? 'border-error bg-error-container/10' : 'border-outline-variant' }}"/>
                    @error('new_password_confirmation')
                        <p class="mt-1.5 text-xs text-error flex items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size:14px;">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-on-primary text-sm font-bold rounded-lg hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary/50">
                        <span class="material-symbols-outlined" style="font-size:18px;">lock_reset</span>
                        ປ່ຽນລະຫັດຜ່ານ
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
