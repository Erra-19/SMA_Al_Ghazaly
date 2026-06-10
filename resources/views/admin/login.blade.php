<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — Admin Al-Ghazaly</title>
    @vite(['resources/css/app.css', 'resources/js/admin.js'])
</head>
<body class="min-h-screen flex items-center justify-center p-4" style="background: linear-gradient(135deg, #050a1a 0%, #0d1035 40%, #191654 100%)">

<div x-data="loginPage()" class="w-full max-w-sm">
    {{-- Logo --}}
    <div class="text-center mb-8">
        <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-white/10 backdrop-blur mb-4">
            <svg class="h-9 w-9 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422A12.083 12.083 0 0112 21.01a12.083 12.083 0 01-6.16-10.432L12 14z" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-white tracking-tight">Al-Ghazaly</h1>
        <p class="text-sm mt-1" style="color:#86c9a8">Panel Admin</p>
    </div>

    {{-- Card --}}
    <div class="rounded-2xl bg-white p-8 shadow-2xl ring-1 ring-white/20">
        <h2 class="text-lg font-semibold text-gray-900 mb-1">Masuk</h2>
        <p class="text-sm text-gray-500 mb-6">Masukkan kredensial akun Anda.</p>

        {{-- Error --}}
        <div x-show="error" x-text="error"
            class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700"></div>

        <form @submit.prevent="submit" class="space-y-4">
            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <input type="email" x-model="email" required placeholder="admin@alghazaly.sch.id"
                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-[#019342] focus:border-transparent
                           placeholder:text-gray-400 transition">
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                <div class="relative">
                    <input :type="showPass ? 'text' : 'password'" x-model="password" required placeholder="••••••••"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 pr-10 text-sm
                               focus:outline-none focus:ring-2 focus:ring-[#019342] focus:border-transparent
                               placeholder:text-gray-400 transition">
                    <button type="button" @click="showPass = !showPass"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg x-show="!showPass" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="showPass" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" :disabled="loading"
                class="w-full rounded-lg disabled:opacity-60 text-white font-semibold py-2.5 text-sm transition mt-2"
                style="background:#019342;" onmouseover="this.style.background='#191654'" onmouseout="this.style.background='#019342'">
                <span x-show="!loading">Masuk</span>
                <span x-show="loading">Memproses...</span>
            </button>
        </form>
    </div>
</div>

</body>
</html>
