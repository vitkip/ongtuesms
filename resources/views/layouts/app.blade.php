<!DOCTYPE html>
<html class="light" lang="lo">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'ລະບົບ Ongtue SMS') - ວິທະຍາໄລຄູສົງ ອົງຕື້</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Phetsarath:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Phetsarath', sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Phetsarath', serif;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #00327d;
            border-radius: 10px;
        }

        /* Sidebar transition */
        #sidebar {
            transition: transform 0.3s ease;
        }
        #sidebar-overlay {
            transition: opacity 0.3s ease;
        }
    </style>
    @livewireStyles
    @stack('styles')
</head>
<body class="bg-background text-on-background font-sans">

    <!-- Sidebar Overlay (mobile) -->
    <div id="sidebar-overlay"
         class="fixed inset-0 z-30 bg-black/50 opacity-0 pointer-events-none lg:hidden"
         onclick="closeSidebar()">
    </div>

    <!-- Side Navigation Bar -->
    <aside id="sidebar"
           class="flex flex-col h-screen w-64 fixed left-0 top-0 z-40 bg-primary border-r border-outline-variant -translate-x-full lg:translate-x-0">

        <!-- Sidebar Header with close button (mobile) -->
        <div class="p-6 flex flex-col items-center border-b border-on-primary/10 relative">
            <button onclick="closeSidebar()"
                    class="absolute top-3 right-3 p-1.5 rounded-full text-on-primary/70 hover:text-on-primary hover:bg-primary-container/30 transition-colors lg:hidden">
                <span class="material-symbols-outlined" style="font-size:20px;">close</span>
            </button>
            <div class="w-16 h-16 bg-on-primary rounded-full flex items-center justify-center mb-4 overflow-hidden shadow-md">
                <img class="w-full h-full object-contain p-1.5"
                     alt="Official seal of Ongtue Sangha Teacher Training College"
                     src="{{ setting('school_logo') ? asset('storage/' . setting('school_logo')) : 'https://lh3.googleusercontent.com/aida-public/AB6AXuCZ4pw8785vErjuHxiEBrnEg7H2H2X9HMs3U6znRrbPzZcNiob5Rd6v9MtkP-4yavkH8HuzWghTOlXXTLnDLvgK1MZsPGr4MeBUI-SYVoeS_xmCIlv5a3mWKRJIyb3FwyeViiPKDj3ekuEv-hdC5Ms7YQ66yMbNE9YDUREOP9bvmkgFQgvJbbFZh-kHDqehvgIQA9bLjNwnGWpGRp7grQTN4WJWbUcbrIdTJt2Q6DO1dh63eUMw77hjmNw3UnvwesBTbhTVDvK00wU' }}"/>
            </div>
            <h1 class="text-lg font-bold text-on-primary text-center leading-tight">{{ setting('school_name', 'ວິທະຍາໄລຄູສົງ ອົງຕື້') }}</h1>
            <p class="text-[10px] text-on-primary/70 mt-1 uppercase tracking-wider font-semibold">{{ setting('school_name_en') ? setting('school_name_en') : 'Teacher Training College' }}</p>
        </div>

        <nav class="flex-1 mt-6 px-2 overflow-y-auto custom-scrollbar space-y-1">
            <!-- Dashboard -->
            <a class="px-4 py-3 flex items-center gap-3 rounded-lg transition-all text-sm font-semibold {{ Route::is('dashboard') ? 'bg-primary-container text-on-primary border-l-4 border-secondary-container' : 'text-on-primary/70 hover:text-on-primary hover:bg-primary-container/30' }}"
               href="{{ route('dashboard') }}" onclick="closeSidebar()">
                <span class="material-symbols-outlined">dashboard</span>
                <span>ແຜງຄວບຄຸມ (Dashboard)</span>
            </a>

            @can('admin')
            <!-- Student Management -->
            <a class="px-4 py-3 flex items-center gap-3 rounded-lg transition-all text-sm font-semibold {{ Route::is('students*') ? 'bg-primary-container text-on-primary border-l-4 border-secondary-container' : 'text-on-primary/70 hover:text-on-primary hover:bg-primary-container/30' }}"
               href="{{ route('students') }}" onclick="closeSidebar()">
                <span class="material-symbols-outlined">group</span>
                <span>ຈັດການຂໍ້ມູນນັກສຶກສາ</span>
            </a>

            <!-- Academic & Curriculum -->
            <a class="px-4 py-3 flex items-center gap-3 rounded-lg transition-all text-sm font-semibold {{ Route::is('academic*') || Route::is('curriculum*') || Route::is('subjects*') || Route::is('majors*') ? 'bg-primary-container text-on-primary border-l-4 border-secondary-container' : 'text-on-primary/70 hover:text-on-primary hover:bg-primary-container/30' }}"
               href="{{ route('academic') }}" onclick="closeSidebar()">
                <span class="material-symbols-outlined">menu_book</span>
                <span>ຫຼັກສູດ ແລະ ວິຊາຮຽນ</span>
            </a>
            @endcan

            @can('staff')
            <!-- Enrollment & Grades -->
            <a class="px-4 py-3 flex items-center gap-3 rounded-lg transition-all text-sm font-semibold {{ Route::is('enrollments*') || Route::is('grades*') ? 'bg-primary-container text-on-primary border-l-4 border-secondary-container' : 'text-on-primary/70 hover:text-on-primary hover:bg-primary-container/30' }}"
               href="{{ route('grades') }}" onclick="closeSidebar()">
                <span class="material-symbols-outlined">grade</span>
                <span>ການລົງທະບຽນ & ຄະແນນ</span>
            </a>
            @endcan

            @can('cashier')
            <!-- Finance -->
            <a class="px-4 py-3 flex items-center gap-3 rounded-lg transition-all text-sm font-semibold {{ Route::is('invoices*') ? 'bg-primary-container text-on-primary border-l-4 border-secondary-container' : 'text-on-primary/70 hover:text-on-primary hover:bg-primary-container/30' }}"
               href="{{ route('invoices') }}" onclick="closeSidebar()">
                <span class="material-symbols-outlined">payments</span>
                <span>ລະບົບການເງິນ</span>
            </a>
            @endcan

            @can('admin')
            <!-- User Management -->
            <a class="px-4 py-3 flex items-center gap-3 rounded-lg transition-all text-sm font-semibold {{ Route::is('users*') ? 'bg-primary-container text-on-primary border-l-4 border-secondary-container' : 'text-on-primary/70 hover:text-on-primary hover:bg-primary-container/30' }}"
               href="{{ route('users') }}" onclick="closeSidebar()">
                <span class="material-symbols-outlined">manage_accounts</span>
                <span>ຈັດການຜູ້ໃຊ້ລະບົບ</span>
            </a>
            @endcan
        </nav>

        <div class="p-4 border-t border-on-primary/10">
            <div class="flex flex-col gap-1">
                @can('admin')
                <a class="text-on-primary/70 hover:text-on-primary px-4 py-2 flex items-center gap-3 text-sm transition-colors rounded-lg hover:bg-primary-container/20 {{ Route::is('settings*') ? 'bg-primary-container text-on-primary border-l-4 border-secondary-container' : '' }}"
                   href="{{ route('settings') }}" onclick="closeSidebar()">
                    <span class="material-symbols-outlined">settings</span>
                    <span>ຕັ້ງຄ່າລະບົບ</span>
                </a>
                @endcan

                <a class="text-on-primary/70 hover:text-on-primary px-4 py-2 flex items-center gap-3 text-sm transition-colors rounded-lg hover:bg-primary-container/20 {{ Route::is('profile') ? 'bg-primary-container text-on-primary border-l-4 border-secondary-container' : '' }}"
                   href="{{ route('profile') }}" onclick="closeSidebar()">
                    <span class="material-symbols-outlined">account_circle</span>
                    <span>ໂປຣໄຟລ໌ຂ້ອຍ</span>
                </a>

                <form action="{{ route('logout') }}" method="POST" id="logout-form" class="hidden">
                    @csrf
                </form>
                <a class="text-on-primary/70 hover:text-on-primary px-4 py-2 flex items-center gap-3 text-sm transition-colors rounded-lg hover:bg-primary-container/20 cursor-pointer"
                   onclick="document.getElementById('logout-form').submit();">
                    <span class="material-symbols-outlined">logout</span>
                    <span>ອອກຈາກລະບົບ</span>
                </a>
            </div>
        </div>
    </aside>

    <!-- Top App Bar -->
    <header class="flex justify-between items-center h-16 px-4 md:px-6 lg:px-10 sticky top-0 z-30 bg-surface-container-lowest border-b border-outline-variant shadow-sm lg:ml-64">

        <div class="flex items-center gap-3">
            <!-- Hamburger (mobile/tablet) -->
            <button onclick="openSidebar()"
                    class="p-2 rounded-full hover:bg-surface-container-low transition-colors lg:hidden"
                    aria-label="ເປີດເມນູ">
                <span class="material-symbols-outlined text-primary" style="font-size:24px;">menu</span>
            </button>
            <span class="text-sm font-bold text-primary hidden sm:block">{{ setting('school_name', 'ວິທະຍາໄລຄູສົງ ອົງຕື້') }}{{ setting('school_name_en') ? ' | ' . setting('school_name_en') : '' }}</span>
            <!-- Short name on very small screens -->
            <span class="text-sm font-bold text-primary sm:hidden">{{ setting('school_name_short', 'OTS') }}</span>
        </div>

        <div class="flex items-center gap-2 md:gap-4">
            <!-- Search (hidden on small, visible on md+) -->
            <div class="relative hidden md:block">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline" style="font-size: 18px;">search</span>
                <input class="pl-10 pr-4 py-1.5 bg-surface-container rounded-full border-none focus:ring-2 focus:ring-primary w-48 lg:w-64 text-sm"
                       placeholder="ຄົ້ນຫາຂໍ້ມູນ..."
                       type="text"/>
            </div>

            <div class="flex items-center gap-1 md:gap-2">
                <!-- Search toggle (mobile only) -->
                <button class="p-2 hover:bg-surface-container-low rounded-full transition-colors md:hidden">
                    <span class="material-symbols-outlined text-primary" style="font-size: 22px;">search</span>
                </button>

                <button class="p-2 hover:bg-surface-container-low rounded-full transition-colors relative">
                    <span class="material-symbols-outlined text-primary" style="font-size: 22px;">notifications</span>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-error rounded-full"></span>
                </button>
                <button class="p-2 hover:bg-surface-container-low rounded-full transition-colors hidden sm:block">
                    <span class="material-symbols-outlined text-primary" style="font-size: 22px;">help</span>
                </button>
                <div class="h-8 w-[1px] bg-outline-variant mx-1 hidden sm:block"></div>
                <a href="{{ route('profile') }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity" title="ໂປຣໄຟລ໌ຂ້ອຍ">
                    <div class="w-8 h-8 rounded-full bg-primary border border-primary/20 flex-shrink-0 flex items-center justify-center">
                        <span class="text-on-primary text-sm font-bold leading-none">
                            {{ mb_strtoupper(mb_substr(Auth::user()->full_name ?? Auth::user()->username, 0, 1)) }}
                        </span>
                    </div>
                    <div class="hidden xl:block">
                        <p class="text-xs font-bold leading-none">{{ Auth::user()->full_name ?? 'ຜູ້ບໍລິຫານ' }}</p>
                        <p class="text-[10px] text-outline leading-none mt-1 font-semibold uppercase">{{ Auth::user()->role === 'admin' ? 'ຜູ້ດູແລລະບົບ' : (Auth::user()->role === 'finance' ? 'ພະນັກງານການເງິນ' : 'ອາຈານສອນ') }}</p>
                    </div>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="lg:ml-64 p-4 md:p-6 lg:p-10 bg-background min-h-[calc(100vh-4rem)]">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    @livewireScripts
    @stack('scripts')
    <script>
        function openSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            overlay.classList.add('opacity-100');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0');
            overlay.classList.add('opacity-0', 'pointer-events-none');
            overlay.classList.remove('opacity-100');
            document.body.style.overflow = '';
        }

        // Close sidebar on resize to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                closeSidebar();
                document.body.style.overflow = '';
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.hover-lift');
            cards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.style.transform = 'translateY(-4px)';
                    card.style.transition = 'all 0.3s ease';
                });
                card.addEventListener('mouseleave', () => {
                    card.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>
</html>
