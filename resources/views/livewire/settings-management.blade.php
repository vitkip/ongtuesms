<div>
    <!-- Header Section with Banner -->
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
                    <span class="material-symbols-outlined text-secondary-container text-[18px]">settings</span>
                    <span class="text-secondary-container text-[10px] font-bold uppercase tracking-widest">ລະບົບບໍລິຫານຈັດການ</span>
                </div>
                <h2 class="text-3xl font-bold text-white mb-2">ຕັ້ງຄ່າລະບົບ (System Settings)</h2>
                <p class="text-sm text-white/65 max-w-2xl">
                    ຈັດການຂໍ້ມູນພື້ນຖານຂອງສະຖາບັນ, ການຕັ້ງຄ່າການເງິນ ແລະ ລະບົບການແຈ້ງເຕືອນຂອງວິທະຍາໄລ
                </p>
            </div>
        </div>
        
        <!-- Watermark -->
        <div class="absolute right-4 top-0 h-full flex items-center opacity-[0.05] pointer-events-none">
            <span class="material-symbols-outlined text-white" style="font-size: 200px; font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 48;">settings</span>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-xl text-sm flex items-center gap-3 shadow-sm transition-all duration-300">
            <span class="material-symbols-outlined text-emerald-600 shrink-0" style="font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;">check_circle</span>
            <span class="font-semibold">{{ session('message') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="saveSettings" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8" x-data="{ activeTab: 'institution' }">
            <!-- Sidebar Navigation -->
            <div class="space-y-2 lg:col-span-1">
                <button type="button" 
                        @click="activeTab = 'institution'" 
                        :class="activeTab === 'institution' ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface-container-lowest text-outline border border-outline-variant hover:text-on-background hover:bg-surface-container-low'"
                        class="w-full px-4 py-3.5 rounded-xl font-bold transition-all flex items-center gap-3 text-sm text-left active:scale-[0.98]">
                    <span class="material-symbols-outlined text-[20px]">school</span>
                    <span>ຂໍ້ມູນສະຖາບັນ</span>
                </button>
                <button type="button" 
                        @click="activeTab = 'financial'" 
                        :class="activeTab === 'financial' ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface-container-lowest text-outline border border-outline-variant hover:text-on-background hover:bg-surface-container-low'"
                        class="w-full px-4 py-3.5 rounded-xl font-bold transition-all flex items-center gap-3 text-sm text-left active:scale-[0.98]">
                    <span class="material-symbols-outlined text-[20px]">account_balance</span>
                    <span>ການເງິນ &amp; QR Code</span>
                </button>
                <button type="button" 
                        @click="activeTab = 'notifications'" 
                        :class="activeTab === 'notifications' ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface-container-lowest text-outline border border-outline-variant hover:text-on-background hover:bg-surface-container-low'"
                        class="w-full px-4 py-3.5 rounded-xl font-bold transition-all flex items-center gap-3 text-sm text-left active:scale-[0.98]">
                    <span class="material-symbols-outlined text-[20px]">notifications_active</span>
                    <span>ລະບົບແຈ້ງເຕືອນ</span>
                </button>
            </div>

            <!-- Content Card -->
            <div class="lg:col-span-3 bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-sm flex flex-col min-h-[480px]">
                <div class="p-6 flex-1">
                    
                    <!-- Tab 1: Institution Details -->
                    <div x-show="activeTab === 'institution'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                        <div>
                            <h3 class="text-base font-bold text-primary flex items-center gap-2">
                                <span class="material-symbols-outlined text-[22px]">school</span>
                                <span>ຂໍ້ມູນທົ່ວໄປຂອງສະຖາບັນ</span>
                            </h3>
                            <p class="text-[11px] text-outline mt-1">ຕັ້ງຄ່າຂໍ້ມູນ ແລະ ໂລໂກ້ຫຼັກທີ່ຈະສະແດງໃນລະບົບ ແລະ ໃບບິນ</p>
                        </div>
                        <div class="h-px bg-outline-variant/50"></div>

                        <!-- Institution Logo Upload -->
                        <div class="flex flex-col md:flex-row items-center gap-6 pb-6 border-b border-outline-variant/30">
                            <div class="relative w-24 h-24 rounded-full border-2 border-primary/20 bg-background overflow-hidden flex items-center justify-center group shadow-md shrink-0">
                                @if ($logo)
                                    <img src="{{ $logo->temporaryUrl() }}" class="w-full h-full object-contain p-2"/>
                                @elseif ($existing_logo)
                                    <img src="{{ asset('storage/' . $existing_logo) }}" class="w-full h-full object-contain p-2"/>
                                @else
                                    <img src="{{ asset('logo.png') }}" class="w-full h-full object-contain p-2"/>
                                @endif
                                
                                <!-- Loading Spinner for Upload -->
                                <div wire:loading wire:target="logo" class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                    <svg class="animate-spin h-6 w-6 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="space-y-2 text-center md:text-left flex-1">
                                <label class="block text-xs font-bold text-primary uppercase tracking-wide">ໂລໂກ້ສະຖາບັນ (Institution Logo)</label>
                                <div class="flex flex-col sm:flex-row items-center justify-center md:justify-start gap-3">
                                    <label class="cursor-pointer bg-primary hover:bg-primary-container text-on-primary px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-sm active:scale-[0.98]">
                                        <span>ເລືອກຮູບພາບໃໝ່</span>
                                        <input wire:model="logo" type="file" class="hidden" accept="image/*"/>
                                    </label>
                                    <span class="text-[10px] text-outline">ຮອງຮັບ PNG, JPG, JPEG ຂະໜາດບໍ່ເກີນ 2MB</span>
                                </div>
                                @error('logo') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- School Name Lao -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-on-surface">ຊື່ວິທະຍາໄລ (ພາສາລາວ) *</label>
                                <input wire:model="school_name" type="text" class="w-full bg-background border border-outline-variant rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"/>
                                @error('school_name') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- School Name EN -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-on-surface">ຊື່ວິທະຍາໄລ (ພາສາອັງກິດ)</label>
                                <input wire:model="school_name_en" type="text" class="w-full bg-background border border-outline-variant rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"/>
                                @error('school_name_en') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- School Address -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-on-surface">ທີ່ຢູ່ຂອງສະຖາບັນ *</label>
                            <input wire:model="school_address" type="text" class="w-full bg-background border border-outline-variant rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"/>
                            @error('school_address') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- School Phone -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-on-surface">ເບີໂທຕິດຕໍ່</label>
                                <input wire:model="school_phone" type="text" class="w-full bg-background border border-outline-variant rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"/>
                                @error('school_phone') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- School Email -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-on-surface">ອີເມລຕິດຕໍ່</label>
                                <input wire:model="school_email" type="email" class="w-full bg-background border border-outline-variant rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"/>
                                @error('school_email') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Tab 2: Financial Configuration -->
                    <div x-show="activeTab === 'financial'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                        <div>
                            <h3 class="text-base font-bold text-primary flex items-center gap-2">
                                <span class="material-symbols-outlined text-[22px]">account_balance</span>
                                <span>การตั้งค่าการเงินและธนาคาร</span>
                            </h3>
                            <p class="text-[11px] text-outline mt-1">ກຳນົດບັນຊີທະນາຄານຫຼັກ ແລະ QR Code ເພື່ອຮັບໂอนຄ່າຮຽນ</p>
                        </div>
                        <div class="h-px bg-outline-variant/50"></div>

                        <!-- Bank Account Number -->
                        <div class="space-y-1.5 max-w-md">
                            <label class="block text-xs font-bold text-on-surface">ເລກບັນຊີຮັບໂອນເງິນຫຼັກ (BCEL LAK) *</label>
                            <input wire:model="bank_account_number" type="text" class="w-full bg-background border border-outline-variant rounded-xl px-4 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"/>
                            <p class="text-[10px] text-outline/80 mt-1 leading-normal">ໃຊ້ເພື່ອສ້າງ QR Code ໃນການໂອນຄ່າທຳນຽມຂອງນັກສຶກສາໂດຍອັດຕະໂນມັດ.</p>
                            @error('bank_account_number') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Bank QR Code Upload -->
                        <div class="flex flex-col md:flex-row items-center gap-6 pt-4 border-t border-outline-variant/30">
                            <div class="relative w-36 h-36 rounded-2xl border-2 border-primary/20 bg-background overflow-hidden flex items-center justify-center group shadow-md shrink-0">
                                @if ($bank_qr_code)
                                    <img src="{{ $bank_qr_code->temporaryUrl() }}" class="w-full h-full object-contain p-2"/>
                                @elseif ($existing_bank_qr_code)
                                    <img src="{{ asset('storage/' . $existing_bank_qr_code) }}" class="w-full h-full object-contain p-2"/>
                                @else
                                    <div class="flex flex-col items-center justify-center text-outline/60 gap-1.5">
                                        <span class="material-symbols-outlined text-4xl">qr_code_2</span>
                                        <span class="text-[10px] font-bold">ຍັງບໍ່ມີຮູບ QR</span>
                                    </div>
                                @endif

                                <!-- Loading Spinner for QR Upload -->
                                <div wire:loading wire:target="bank_qr_code" class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                    <svg class="animate-spin h-6 w-6 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </div>

                            <div class="space-y-2 text-center md:text-left flex-1">
                                <label class="block text-xs font-bold text-primary uppercase tracking-wide">QR Code ບັນຊີທະນາຄານ (Bank QR Code)</label>
                                <div class="flex flex-col sm:flex-row items-center justify-center md:justify-start gap-3">
                                    <label class="cursor-pointer bg-primary hover:bg-primary-container text-on-primary px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-sm active:scale-[0.98]">
                                        <span>ເລືອກຮູບ QR Code</span>
                                        <input wire:model="bank_qr_code" type="file" class="hidden" accept="image/*"/>
                                    </label>
                                    <span class="text-[10px] text-outline">ຮອງຮັບ PNG, JPG, JPEG ຂະໜາດບໍ່ເກີນ 2MB</span>
                                </div>
                                <p class="text-[10px] text-outline/80 leading-normal mt-1">ອັບໂຫລດຮູບ QR Code ຂອງບັນຊີທະນາຄານ ເພື່ອໃຊ້ສະແດງໃນໃບຮຽກເກັບຄ່າທຳນຽມ.</p>
                                @error('bank_qr_code') <span class="text-xs text-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Tab 3: Notification Configuration -->
                    <div x-show="activeTab === 'notifications'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                        <div>
                            <h3 class="text-base font-bold text-primary flex items-center gap-2">
                                <span class="material-symbols-outlined text-[22px]">notifications_active</span>
                                <span>ລະບົບການແຈ້ງເຕືອນ (Notification System)</span>
                            </h3>
                            <p class="text-[11px] text-outline mt-1">ຕັ້ງຄ່າການແຈ້ງເຕືອນອັດຕະໂນມັດໄປຫາຜູ້ໃຊ້ ແລະ ນັກສຶກສາ</p>
                        </div>
                        <div class="h-px bg-outline-variant/50"></div>

                        <!-- Email Notification Toggle Switch -->
                        <div class="p-5 border border-outline-variant/60 rounded-2xl bg-surface-container-low flex items-start gap-4">
                            <div class="pt-0.5">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input wire:model="enable_email_notifications" type="checkbox" id="enable_email" class="sr-only peer" value="1" {{ $enable_email_notifications == '1' ? 'checked' : '' }}/>
                                    <div class="w-11 h-6 bg-outline-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                </label>
                            </div>
                            <div class="flex-1">
                                <label for="enable_email" class="text-sm font-bold text-on-background cursor-pointer">ເປີດໃຊ້ການແຈ້ງເຕືອນຜ່ານອີເມລ (Email Notifications)</label>
                                <p class="text-xs text-outline leading-normal mt-1">ເມື່ອເປີດໃຊ້ງານ, ລະບົບຈະສົ່ງໃບບິນຄ່າທຳນຽມຫາອີເມລຂອງນັກສຶກສາອັດຕະໂນມັດເມື່ອມີການສ້າງໃບບິນໃໝ່.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Global Action Footer -->
                <div class="px-6 py-5 border-t border-outline-variant bg-surface-container-low flex justify-end">
                    <button type="submit" 
                            class="bg-primary hover:bg-primary-container text-on-primary px-8 py-3 rounded-xl font-bold shadow-md hover:shadow-lg active:scale-[0.98] transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">save</span>
                        <span>ບັນທຶກການຕັ້ງຄ່າທັງໝົດ</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
