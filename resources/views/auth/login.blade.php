<!DOCTYPE html>
<html class="light" lang="lo">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>ເຂົ້າສູ່ລະບົບ - Ongtue SMS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Phetsarath:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Phetsarath', sans-serif;
        }
        h1, h2, h3 {
            font-family: 'Phetsarath', serif;
        }
    </style>
</head>
<body class="bg-background text-on-background min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-surface-container-lowest border border-outline-variant rounded-xl shadow-xl overflow-hidden p-8">
        <!-- Header / Logo -->
        <div class="flex flex-col items-center mb-8 text-center">
            <div class="w-20 h-20 bg-primary/5 rounded-full flex items-center justify-center mb-4 p-2 border border-primary/10">
                <img class="w-full h-full object-contain" 
                     alt="Official Logo of Ongtue Sangha Teacher Training College" 
                     src="{{ setting('school_logo') ? asset('storage/' . setting('school_logo')) : 'https://lh3.googleusercontent.com/aida-public/AB6AXuCZ4pw8785vErjuHxiEBrnEg7H2H2X9HMs3U6znRrbPzZcNiob5Rd6v9MtkP-4yavkH8HuzWghTOlXXTLnDLvgK1MZsPGr4MeBUI-SYVoeS_xmCIlv5a3mWKRJIyb3FwyeViiPKDj3ekuEv-hdC5Ms7YQ66yMbNE9YDUREOP9bvmkgFQgvJbbFZh-kHDqehvgIQA9bLjNwnGWpGRp7grQTN4WJWbUcbrIdTJt2Q6DO1dh63eUMw77hjmNw3UnvwesBTbhTVDvK00wU' }}"/>
            </div>
            <h1 class="text-2xl font-bold text-primary">{{ setting('school_name', 'ວິທະຍາໄລຄູສົງ ອົງຕື້') }}</h1>
            <p class="text-sm text-outline mt-1 font-semibold uppercase tracking-wider">{{ setting('school_name_en') ? setting('school_name_en') : 'Sangha Teacher Training College' }}</p>
            <p class="text-xs text-on-surface-variant/70 mt-1">ລະບົບຈັດການຂໍ້ມູນນັກສຶກສາ (Ongtue SMS)</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-error-container text-on-error-container rounded-lg text-sm flex items-start gap-2 border border-error/20">
                <span class="material-symbols-outlined text-error" style="font-size: 20px;">error</span>
                <div>
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-bold text-primary mb-2">ຊື່ຜູ້ໃຊ້ (Username)</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline" style="font-size: 20px;">person</span>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           value="{{ old('username') }}" 
                           required 
                           autofocus
                           class="w-full pl-10 pr-4 py-3 bg-background border border-outline-variant rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition-all"
                           placeholder="ປ້ອນຊື່ຜູ້ໃຊ້ຂອງທ່ານ..."/>
                </div>
            </div>

            <!-- Password -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label for="password" class="block text-sm font-bold text-primary">ລະຫັດຜ່ານ (Password)</label>
                </div>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline" style="font-size: 20px;">lock</span>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required 
                           class="w-full pl-10 pr-4 py-3 bg-background border border-outline-variant rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition-all"
                           placeholder="ປ້ອນລະຫັດຜ່ານຂອງທ່ານ..."/>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input type="checkbox" 
                       id="remember" 
                       name="remember" 
                       class="h-4 w-4 text-primary focus:ring-primary border-outline-variant rounded"/>
                <label for="remember" class="ml-2 block text-xs font-semibold text-on-surface-variant">ຈົດຈຳຂ້ອຍໃນລະບົບ</label>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="w-full bg-primary hover:bg-primary-container text-on-primary font-bold py-3 px-4 rounded-lg flex items-center justify-center gap-2 hover:opacity-95 shadow-md hover:shadow-lg active:scale-[0.99] transition-all duration-200">
                    <span class="material-symbols-outlined" style="font-size: 20px;">login</span>
                    <span>ເຂົ້າສູ່ລະບົບ</span>
                </button>
            </div>
        </form>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-outline-variant text-center">
            <p class="text-[11px] text-outline font-medium">
                &copy; {{ date('Y') }} ວິທະຍາໄລຄູສົງ ອົງຕື້. ສະຫງວນລິຂະສິດ.
            </p>
        </div>
    </div>

</body>
</html>
