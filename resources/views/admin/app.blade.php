<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin — Al-Ghazaly</title>
    @vite(['resources/css/app.css', 'resources/js/admin.js'])
</head>

<body class="min-h-screen bg-gray-50" x-data x-init="$store.adm.boot()" x-cloak>

{{-- Toast --}}
<div x-show="$store.adm.toast.show"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="translate-y-2 opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed top-5 right-5 z-[100] flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium shadow-lg min-w-[260px]"
    :class="$store.adm.toast.type === 'success' ? 'bg-green-600 text-white' : 'bg-red-500 text-white'">
    <svg x-show="$store.adm.toast.type === 'success'" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
    <svg x-show="$store.adm.toast.type !== 'success'" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
    <span x-text="$store.adm.toast.msg"></span>
</div>

<div class="flex h-screen overflow-hidden">

{{-- ═══ SIDEBAR ═══════════════════════════════════════════════════════════════ --}}
<aside class="flex w-[220px] shrink-0 flex-col overflow-y-auto" style="background:#0d1035;">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-5 py-5 border-b" style="border-color:rgba(255,255,255,.07)">
        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg" style="background:#019342;">
            <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422A12.083 12.083 0 0112 21.01a12.083 12.083 0 01-6.16-10.432L12 14z"/>
            </svg>
        </div>
        <div>
            <p class="text-[13px] font-bold text-white leading-tight">Al-Ghazaly</p>
            <p class="text-[11px] mt-0.5" style="color:#4ade80">Admin Panel</p>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5">

        <a @click.prevent="$store.adm.go('dashboard')" href="#"
            class="adm-sidebar-link" :class="$store.adm.page === 'dashboard' ? 'active' : ''">
            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </a>

        {{-- PPDB --}}
        <div x-data="{ open: ['registrations','payments'].includes($store.adm.page) }">
            <button @click="open = !open" class="adm-sidebar-group w-full"
                :style="['registrations','payments'].includes($store.adm.page) ? 'background:rgba(255,255,255,.06)' : ''">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span class="flex-1 text-left">PPDB</span>
                <svg class="h-3 w-3 transition-transform duration-200" :class="open && 'rotate-90'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="open" x-transition class="ml-[22px] mt-0.5 border-l pl-3 space-y-0.5" style="border-color:rgba(255,255,255,.1)">
                <a @click.prevent="$store.adm.go('registrations')" href="#" class="adm-sidebar-link text-[13px] py-1.5" :class="$store.adm.page === 'registrations' ? 'active' : ''">Pendaftaran</a>
                <a @click.prevent="$store.adm.go('payments')" href="#" class="adm-sidebar-link text-[13px] py-1.5" :class="$store.adm.page === 'payments' ? 'active' : ''">Pembayaran</a>
            </div>
        </div>

        {{-- Konten --}}
        <div x-data="{ open: ['posts','categories','programs','facilities','academic-calendars','form-builder','testimonials'].includes($store.adm.page) }">
            <button @click="open = !open" class="adm-sidebar-group w-full"
                :style="['posts','categories','programs','facilities','academic-calendars','form-builder','testimonials'].includes($store.adm.page) ? 'background:rgba(255,255,255,.06)' : ''">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                <span class="flex-1 text-left">Konten</span>
                <svg class="h-3 w-3 transition-transform duration-200" :class="open && 'rotate-90'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="open" x-transition class="ml-[22px] mt-0.5 border-l pl-3 space-y-0.5" style="border-color:rgba(255,255,255,.1)">
                <a @click.prevent="$store.adm.go('posts')" href="#" class="adm-sidebar-link text-[13px] py-1.5" :class="$store.adm.page === 'posts' ? 'active' : ''">Berita & Artikel</a>
                <a @click.prevent="$store.adm.go('categories')" href="#" class="adm-sidebar-link text-[13px] py-1.5" :class="$store.adm.page === 'categories' ? 'active' : ''">Kategori</a>
                <a @click.prevent="$store.adm.go('programs')" href="#" class="adm-sidebar-link text-[13px] py-1.5" :class="$store.adm.page === 'programs' ? 'active' : ''">Program</a>
                <a @click.prevent="$store.adm.go('facilities')" href="#" class="adm-sidebar-link text-[13px] py-1.5" :class="$store.adm.page === 'facilities' ? 'active' : ''">Fasilitas</a>
                <a @click.prevent="$store.adm.go('academic-calendars')" href="#" class="adm-sidebar-link text-[13px] py-1.5" :class="$store.adm.page === 'academic-calendars' ? 'active' : ''">Kalender Akademik</a>
                <a @click.prevent="$store.adm.go('form-builder')" href="#" class="adm-sidebar-link text-[13px] py-1.5" :class="$store.adm.page === 'form-builder' ? 'active' : ''">Form Builder</a>
                <a @click.prevent="$store.adm.go('testimonials')" href="#" class="adm-sidebar-link text-[13px] py-1.5" :class="$store.adm.page === 'testimonials' ? 'active' : ''">Testimoni</a>
            </div>
        </div>

        {{-- Akademik --}}
        <div x-data="{ open: ['teachers','students','alumni'].includes($store.adm.page) }">
            <button @click="open = !open" class="adm-sidebar-group w-full"
                :style="['teachers','students','alumni'].includes($store.adm.page) ? 'background:rgba(255,255,255,.06)' : ''">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="flex-1 text-left">Akademik</span>
                <svg class="h-3 w-3 transition-transform duration-200" :class="open && 'rotate-90'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="open" x-transition class="ml-[22px] mt-0.5 border-l pl-3 space-y-0.5" style="border-color:rgba(255,255,255,.1)">
                <a @click.prevent="$store.adm.go('teachers')" href="#" class="adm-sidebar-link text-[13px] py-1.5" :class="$store.adm.page === 'teachers' ? 'active' : ''">Guru</a>
                <a @click.prevent="$store.adm.go('students')" href="#" class="adm-sidebar-link text-[13px] py-1.5" :class="$store.adm.page === 'students' ? 'active' : ''">Murid</a>
                <a @click.prevent="$store.adm.go('alumni')" href="#" class="adm-sidebar-link text-[13px] py-1.5" :class="$store.adm.page === 'alumni' ? 'active' : ''">Alumni</a>
            </div>
        </div>

        {{-- Galeri & Media --}}
        <div x-data="{ open: ['albums','media'].includes($store.adm.page) }">
            <button @click="open = !open" class="adm-sidebar-group w-full"
                :style="['albums','media'].includes($store.adm.page) ? 'background:rgba(255,255,255,.06)' : ''">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="flex-1 text-left">Galeri & Media</span>
                <svg class="h-3 w-3 transition-transform duration-200" :class="open && 'rotate-90'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="open" x-transition class="ml-[22px] mt-0.5 border-l pl-3 space-y-0.5" style="border-color:rgba(255,255,255,.1)">
                <a @click.prevent="$store.adm.go('albums')" href="#" class="adm-sidebar-link text-[13px] py-1.5" :class="$store.adm.page === 'albums' ? 'active' : ''">Album Foto</a>
                <a @click.prevent="$store.adm.go('media')" href="#" class="adm-sidebar-link text-[13px] py-1.5" :class="$store.adm.page === 'media' ? 'active' : ''">Media</a>
            </div>
        </div>
        <a @click.prevent="$store.adm.go('messages')" href="#" class="adm-sidebar-link" :class="$store.adm.page === 'messages' ? 'active' : ''">
            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span class="flex-1">Pesan</span>
            <template x-if="$store.adm.internalUnread > 0">
                <span class="ml-auto min-w-[18px] h-[18px] rounded-full bg-rose-500 text-white text-[9px] font-bold flex items-center justify-center px-1"
                    x-text="$store.adm.internalUnread > 99 ? '99+' : $store.adm.internalUnread"></span>
            </template>
        </a>
        <a @click.prevent="$store.adm.go('settings')" href="#" class="adm-sidebar-link" :class="$store.adm.page === 'settings' ? 'active' : ''">
            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Pengaturan
        </a>
        <template x-if="$store.adm.hasRole('super_admin')">
            <a @click.prevent="$store.adm.go('users')" href="#" class="adm-sidebar-link" :class="$store.adm.page === 'users' ? 'active' : ''">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Pengguna
            </a>
        </template>
    </nav>

    {{-- Footer --}}
    <div class="px-5 py-3 border-t" style="border-color:rgba(255,255,255,.07)">
        <p class="text-[11px]" style="color:#4ade80">v1.0 · Al-Ghazaly</p>
    </div>
</aside>

{{-- ═══ MAIN ════════════════════════════════════════════════════════════════ --}}
<div class="flex flex-1 flex-col min-w-0 overflow-hidden">

    {{-- Header --}}
    <header class="flex h-14 shrink-0 items-center justify-between border-b border-gray-200 bg-white px-6">
        <div class="flex items-center gap-2">
            <span class="h-2 w-2 rounded-full inline-block" style="background:#019342;"></span>
            <span class="text-sm font-semibold text-gray-800 capitalize"
                x-text="$store.adm.page.replace(/-/g, ' ')"></span>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2.5">
                <div class="h-7 w-7 rounded-full flex items-center justify-center text-[11px] font-bold text-white" style="background:#019342;"
                    x-text="($store.adm.user?.name ?? 'A').split(' ').slice(0,2).map(n=>n[0]).join('').toUpperCase()"></div>
                <div class="hidden sm:block">
                    <p class="text-xs font-semibold text-gray-900 leading-tight" x-text="$store.adm.user?.name ?? ''"></p>
                    <p class="text-[10px] text-gray-400 capitalize" x-text="($store.adm.user?.role ?? '').replace('_',' ')"></p>
                </div>
            </div>
            <div class="h-5 w-px bg-gray-200"></div>
            <button @click="$store.adm.logout()"
                class="flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs font-medium text-gray-500 hover:bg-red-50 hover:text-red-600 transition-colors">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Keluar
            </button>
        </div>
    </header>

    {{-- Content --}}
    <main class="flex-1 overflow-y-auto p-6">
        @include('admin.sections.dashboard')
        @include('admin.sections.posts')
        @include('admin.sections.categories')
        @include('admin.sections.programs')
        @include('admin.sections.facilities')
        @include('admin.sections.academic-calendars')
        @include('admin.sections.form-builder')
        @include('admin.sections.teachers')
        @include('admin.sections.students')
        @include('admin.sections.albums')
        @include('admin.sections.testimonials')
        @include('admin.sections.alumni')
        @include('admin.sections.media')
        @include('admin.sections.settings')
        @include('admin.sections.registrations')
        @include('admin.sections.payments')
        @include('admin.sections.messages')
        @include('admin.sections.users')
    </main>
</div>

{{-- Global Media Picker --}}
<div x-show="$store.mediaPicker.show" x-cloak class="fixed inset-0 z-[80] flex items-center justify-center p-4" x-transition>
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="$store.mediaPicker.close()"></div>
    <div class="relative w-full max-w-4xl max-h-[82vh] overflow-hidden rounded-2xl bg-white shadow-2xl" @click.stop>
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Pilih Media Gambar</h3>
                <p class="mt-0.5 text-xs text-gray-500">Pilih gambar yang sudah tersimpan di server.</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" @click="$store.mediaPicker.load()" class="adm-btn adm-btn-secondary adm-btn-sm">Refresh</button>
                <button type="button" @click="$store.mediaPicker.close()" class="adm-btn-ghost">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        <div class="max-h-[65vh] overflow-y-auto p-6">
            <div x-show="$store.mediaPicker.loading" class="flex justify-center p-10">
                <svg class="h-6 w-6 animate-spin text-green-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
            </div>
            <div x-show="!$store.mediaPicker.loading && !$store.mediaPicker.items.length" class="rounded-xl border border-dashed border-gray-200 p-10 text-center text-sm text-gray-400">
                Belum ada gambar. Upload dulu dari menu Media atau tombol Upload pada field gambar.
            </div>
            <div x-show="!$store.mediaPicker.loading" class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                <template x-for="file in $store.mediaPicker.items" :key="file.id ?? file.url">
                    <button type="button" @click="$store.mediaPicker.choose(file)" class="group overflow-hidden rounded-xl border border-gray-200 bg-white text-left transition hover:border-green-500 hover:shadow-md">
                        <div class="aspect-square bg-gray-100">
                            <img :src="file.url || ('/storage/' + file.path)" class="h-full w-full object-cover" :alt="file.name || file.filename || 'Media'">
                        </div>
                        <div class="p-2">
                            <p class="truncate text-xs font-medium text-gray-700" x-text="file.name || file.filename || 'Media'"></p>
                        </div>
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>

</div>
</body>
</html>
