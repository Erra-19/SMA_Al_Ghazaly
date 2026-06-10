<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SMA Al-Ghazaly Bogor — Sekolah Islam Unggulan dengan Program Tahfidz, Jalur PTN, Sains & Teknologi">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SMA Al-Ghazaly Bogor</title>
    @vite(['resources/css/app.css', 'resources/js/public.js'])
    <style>
        [x-cloak]{display:none!important}
        .pub-nav-link { transition: color .2s, border-color .2s; }
        .pub-nav-link.active { color: #019342; border-bottom: 2px solid #019342; }
        .pub-section { min-height: 60vh; }
        .green-gradient { background: linear-gradient(135deg, #019342 0%, #027a35 100%); }
        .navy-gradient  { background: linear-gradient(135deg, #0d1035 0%, #191654 100%); }
        .card-hover { transition: transform .2s, box-shadow .2s; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(1,147,66,.15); }
        .spinner { animation: spin 1s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .fade-in { animation: fadeIn .4s ease-out; }
        @keyframes fadeIn { from { opacity:0; transform: translateY(8px); } to { opacity:1; transform: none; } }
    </style>
</head>
<body x-data x-init="$store.pub.init()" x-cloak class="font-sans antialiased bg-gray-50 text-gray-900">

{{-- ═══ HEADER ═══════════════════════════════════════════════════════════════ --}}
<header class="sticky top-0 z-40 shadow-md" style="background: linear-gradient(135deg, #0d1035 0%, #191654 100%)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <button @click="$store.pub.go('home')" class="flex items-center gap-3 group">
                <template x-if="$store.pub.schoolLogo">
                    <img :src="$store.pub.schoolLogo" alt="Logo" class="h-10 w-10 object-contain rounded-full">
                </template>
                <template x-if="!$store.pub.schoolLogo">
                    <div class="h-10 w-10 rounded-full flex items-center justify-center text-white font-black text-base" style="background:#019342">AG</div>
                </template>
                <div class="hidden sm:block text-left">
                    <p class="text-white font-bold text-sm leading-tight" x-text="$store.pub.schoolName"></p>
                    <p class="text-green-300 text-xs">Islamic Senior High School</p>
                </div>
            </button>

            {{-- Desktop Nav --}}
            <nav class="hidden md:flex items-center gap-1">
                @foreach([['home','Beranda'],['profil','Profil'],['program','Program'],['pengajar','Pengajar'],['fasilitas','Fasilitas'],['kontak','Kontak']] as $nav)
                <button
                    @click="$store.pub.go('{{ $nav[0] }}')"
                    :class="$store.pub.tab === '{{ $nav[0] }}' ? 'text-green-400 border-b-2 border-green-400' : 'text-gray-300 hover:text-white border-b-2 border-transparent'"
                    class="pub-nav-link px-3 py-5 text-sm font-medium transition-all">
                    {{ $nav[1] }}
                </button>
                @endforeach
                <button @click="$store.pub.go('ppdb')"
                    class="ml-2 px-5 py-2 rounded-full text-white text-sm font-semibold transition-all hover:opacity-90"
                    style="background:#019342">
                    Daftar PPDB
                </button>
            </nav>

            {{-- Mobile hamburger --}}
            <button class="md:hidden text-gray-300 hover:text-white p-2"
                @click="$store.pub.menuOpen = !$store.pub.menuOpen">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path x-show="!$store.pub.menuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="$store.pub.menuOpen"  stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div x-show="$store.pub.menuOpen" x-transition class="md:hidden border-t border-white/10 bg-navy-900/95">
        @foreach([['home','Beranda'],['profil','Profil'],['program','Program'],['pengajar','Pengajar'],['fasilitas','Fasilitas'],['kontak','Kontak']] as $nav)
        <button
            @click="$store.pub.go('{{ $nav[0] }}')"
            :class="$store.pub.tab === '{{ $nav[0] }}' ? 'text-green-400 bg-white/5' : 'text-gray-300'"
            class="w-full text-left px-6 py-3 text-sm font-medium hover:bg-white/10 transition-colors">
            {{ $nav[1] }}
        </button>
        @endforeach
        <div class="px-4 py-3">
            <button @click="$store.pub.go('ppdb')"
                class="w-full py-2.5 rounded-full text-white text-sm font-semibold"
                style="background:#019342">
                Daftar PPDB
            </button>
        </div>
    </div>
</header>

{{-- ═══ PAGE SECTIONS ════════════════════════════════════════════════════════ --}}
<main>

    <div x-show="$store.pub.tab === 'home'" x-data="homePage()" class="fade-in">
        @include('public.sections.home')
    </div>

    <div x-show="$store.pub.tab === 'profil'" x-data="profilPage()" class="fade-in pub-section">
        @include('public.sections.profil')
    </div>

    <div x-show="$store.pub.tab === 'program'" x-data="programPage()" class="fade-in pub-section">
        @include('public.sections.program')
    </div>

    <div x-show="$store.pub.tab === 'pengajar'" x-data="pengajarPage()" class="fade-in pub-section">
        @include('public.sections.pengajar')
    </div>

    <div x-show="$store.pub.tab === 'fasilitas'" x-data="fasilitasPage()" class="fade-in pub-section">
        @include('public.sections.fasilitas')
    </div>

    <div x-show="$store.pub.tab === 'kontak'" x-data="kontakPage()" class="fade-in pub-section">
        @include('public.sections.kontak')
    </div>

    <div x-show="$store.pub.tab === 'ppdb'" x-data="ppdbPage()" class="fade-in pub-section">
        @include('public.sections.ppdb')
    </div>

</main>

{{-- ═══ FOOTER ════════════════════════════════════════════════════════════════ --}}
<footer style="background:#0d1035">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

            {{-- Brand --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-10 w-10 rounded-full flex items-center justify-center text-white font-black" style="background:#019342">AG</div>
                    <div>
                        <p class="text-white font-bold text-sm" x-text="$store.pub.schoolName"></p>
                        <p class="text-gray-400 text-xs">Islamic Senior High School</p>
                    </div>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">Membentuk generasi Islam berilmu, berkarakter, dan berdaya saing global.</p>
            </div>

            {{-- Quick Links --}}
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm">Navigasi</h4>
                <ul class="space-y-2">
                    @foreach([['home','Beranda'],['profil','Profil Sekolah'],['program','Program Unggulan'],['pengajar','Tenaga Pengajar'],['fasilitas','Fasilitas'],['kontak','Kontak'],['ppdb','PPDB Online']] as $nav)
                    <li>
                        <button @click="$store.pub.go('{{ $nav[0] }}')"
                            class="text-gray-400 hover:text-green-400 text-sm transition-colors">
                            {{ $nav[1] }}
                        </button>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm">Kontak Kami</h4>
                <div class="space-y-3 text-sm text-gray-400">
                    <div class="flex items-start gap-2">
                        <svg class="h-4 w-4 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:#019342"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span x-text="$store.pub.schoolAddress || 'Jl. Raya Bogor, Kota Bogor, Jawa Barat'"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:#019342"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span x-text="$store.pub.schoolPhone || '(0251) 123-4567'"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:#019342"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span x-text="$store.pub.schoolEmail || 'info@alghazaly.sch.id'"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-white/10 mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-gray-500 text-xs" x-text="`© ${new Date().getFullYear()} ` + $store.pub.schoolName + `. Hak cipta dilindungi.`"></p>
            <button @click="$store.pub.go('ppdb')"
                class="px-5 py-2 rounded-full text-white text-xs font-semibold transition-all hover:opacity-90"
                style="background:#019342">
                Daftar Sekarang →
            </button>
        </div>
    </div>
</footer>

</body>
</html>
