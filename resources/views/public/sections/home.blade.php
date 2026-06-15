{{-- ═══ HOME ══════════════════════════════════════════════════════════════════ --}}

{{-- HERO --}}
<section class="relative overflow-hidden" style="background: linear-gradient(135deg, #0d1035 0%, #191654 60%, #1a1f6e 100%); min-height: 88vh">
    {{-- decorative shapes --}}
    <div class="absolute top-0 right-0 w-96 h-96 rounded-full opacity-10" style="background:radial-gradient(circle,#019342,transparent); transform:translate(30%,-30%)"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full opacity-10" style="background:radial-gradient(circle,#019342,transparent); transform:translate(-30%,30%)"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 flex flex-col lg:flex-row items-center gap-12">
        <div class="flex-1 text-center lg:text-left">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-medium mb-5 border"
                 style="background:rgba(1,147,66,.15); color:#4ade80; border-color:rgba(1,147,66,.3)">
                <span class="h-2 w-2 rounded-full inline-block" style="background:#019342"></span>
                Penerimaan Peserta Didik Baru 2026/2027 Dibuka
            </div>

            <h1 class="text-white font-black text-4xl sm:text-5xl lg:text-6xl leading-tight mb-5">
                <span x-text="$store.pub.settings.hero_title || 'Sekolah Islam'"></span><br>
                <span style="color:#019342" x-text="$store.pub.settings.hero_subtitle || 'Unggulan Bogor'"></span>
            </h1>

            <p class="text-gray-300 text-lg leading-relaxed max-w-xl mx-auto lg:mx-0 mb-8"
               x-text="$store.pub.settings.hero_description || 'Membentuk generasi Islam yang cerdas, berakhlak mulia, dan berdaya saing. Dengan program Tahfidz, Jalur PTN, dan kurikulum berbasis karakter Islami.'">
            </p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                <button @click="$store.pub.go('ppdb')"
                    class="px-7 py-3.5 rounded-full text-white font-semibold text-sm transition-all hover:opacity-90 hover:scale-105"
                    style="background:#019342">
                    Daftar Sekarang →
                </button>
                <button @click="$store.pub.go('profil')"
                    class="px-7 py-3.5 rounded-full font-semibold text-sm border-2 border-white/30 text-white hover:bg-white/10 transition-all">
                    Pelajari Lebih Lanjut
                </button>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 gap-4 shrink-0">
            @foreach([['500+','Siswa Aktif'],['30+','Guru Berpengalaman'],['15+','Ekstrakurikuler'],['70%','Lolos PTN']] as $s)
            <div class="rounded-2xl p-5 text-center" style="background:rgba(255,255,255,.07); backdrop-filter:blur(8px)">
                <p class="text-3xl font-black" style="color:#019342">{{ $s[0] }}</p>
                <p class="text-gray-300 text-xs mt-1">{{ $s[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Wave bottom --}}
    <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none" style="height:48px">
        <svg viewBox="0 0 1440 48" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-full">
            <path d="M0 48L60 40C120 32 240 16 360 14C480 12 600 22 720 26C840 30 960 28 1080 22C1200 16 1320 6 1380 2L1440 0V48H0Z" fill="#f9fafb"/>
        </svg>
    </div>
</section>

{{-- PPDB QUICK CHECK --}}
<section class="bg-gray-50 py-10">
    <div class="max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 text-base mb-1">Cek Status Pendaftaran</h3>
            <p class="text-gray-500 text-sm mb-4">Masukkan nomor pendaftaran PPDB untuk melihat status.</p>
            <div class="flex gap-2">
                <input x-model="statusCode"
                    @keydown.enter="checkStatus()"
                    type="text" placeholder="Contoh: PPDB-2026-XXXXXX"
                    class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                    style="--tw-ring-color:#019342">
                <button @click="checkStatus()" :disabled="statusLoading"
                    class="px-5 py-2.5 rounded-xl text-white text-sm font-medium transition-all hover:opacity-90 disabled:opacity-60"
                    style="background:#019342">
                    <span x-show="!statusLoading">Cek</span>
                    <svg x-show="statusLoading" class="spinner h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                </button>
            </div>
            {{-- result --}}
            <template x-if="statusResult">
                <div class="mt-4">
                    <template x-if="statusResult.found">
                        <div class="p-4 rounded-xl bg-green-50 border border-green-100">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold text-sm text-gray-900" x-text="statusResult.student_name"></span>
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium" :class="statusColor(statusResult.status)" x-text="statusBadge(statusResult.status)"></span>
                            </div>
                            <p class="text-xs text-gray-500" x-text="'No. ' + statusResult.registration_number + ' · ' + statusResult.major_choice + ' · ' + statusResult.wave"></p>
                        </div>
                    </template>
                    <template x-if="!statusResult.found">
                        <div class="p-3 rounded-xl bg-red-50 border border-red-100 text-sm text-red-600">
                            Nomor pendaftaran tidak ditemukan. Pastikan nomor sudah benar.
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </div>
</section>

{{-- LOADING --}}
<template x-if="loading">
    <div class="py-20 flex justify-center">
        <svg class="spinner h-8 w-8" style="color:#019342" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
        </svg>
    </div>
</template>

<template x-if="!loading">
<div>

{{-- PENGUMUMAN / BERITA --}}
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color:#019342">Terkini</p>
                <h2 class="text-2xl font-black text-gray-900">Pengumuman & Berita</h2>
            </div>
        </div>

        <template x-if="announcements.length === 0">
            <div class="text-center py-12 text-gray-400">Belum ada pengumuman.</div>
        </template>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <template x-for="ann in announcements" :key="ann.id ?? ann.post_id">
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 card-hover cursor-pointer"
                     @click="selectedPost = ann">
                    <template x-if="ann.image || ann.thumbnail">
                        <img :src="ann.image || ann.thumbnail" class="w-full h-44 object-cover bg-gray-100">
                    </template>
                    <template x-if="!ann.image && !ann.thumbnail">
                        <div class="w-full h-44 flex items-center justify-center" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7)">
                            <svg class="h-12 w-12" style="color:#86efac" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        </div>
                    </template>
                    <div class="p-4">
                        {{-- badges: categories + status --}}
                        <div class="flex items-center gap-1.5 flex-wrap mb-2">
                            <template x-for="cat in (ann.categories ?? []).slice(0,2)" :key="cat.category_id ?? cat.slug">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wide bg-green-100 text-green-700"
                                    x-text="cat.category_name"></span>
                            </template>
                            <template x-if="ann.post_status === 'Penting'">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wide bg-red-100 text-red-600">Penting</span>
                            </template>
                        </div>
                        <h3 class="font-semibold text-gray-900 text-sm line-clamp-2 mb-1.5" x-text="ann.title"></h3>
                        <p class="text-xs text-gray-500 line-clamp-2 mb-3" x-text="ann.summary || excerpt(ann.content)"></p>
                        {{-- footer: author + date --}}
                        <div class="flex items-center justify-between pt-2.5 border-t border-gray-100">
                            <div class="flex items-center gap-1.5 min-w-0">
                                <svg class="h-3 w-3 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span class="text-[10px] text-gray-400 font-medium truncate" x-text="ann.author?.name ?? 'Admin'"></span>
                            </div>
                            <span class="text-[10px] text-gray-400 shrink-0 ml-2" x-text="fmtDateShort(ann.published_at || ann.created_at)"></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</section>

{{-- PROGRAM UNGGULAN TEASER --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color:#019342">Keunggulan Kami</p>
            <h2 class="text-2xl font-black text-gray-900">Program Unggulan</h2>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach([['📖','Tahfidz Al-Quran','Target 5–30 Juz'],['🎓','Jalur PTN','70%+ alumni PTN'],['🔬','Sains & Teknologi','Lab & olimpiade'],['🌟','Leadership','Karakter Islami']] as $p)
            <div class="rounded-2xl p-5 text-center card-hover cursor-pointer bg-gray-50 hover:bg-green-50 border border-gray-100 hover:border-green-200 transition-all"
                 @click="$store.pub.go('program')">
                <div class="text-4xl mb-3">{{ $p[0] }}</div>
                <p class="font-bold text-gray-900 text-sm">{{ $p[1] }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $p[2] }}</p>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <button @click="$store.pub.go('program')"
                class="px-6 py-2.5 rounded-full border-2 font-medium text-sm transition-all hover:text-white"
                style="border-color:#019342; color:#019342"
                onmouseover="this.style.background='#019342'"
                onmouseout="this.style.background='transparent'">
                Lihat Semua Program
            </button>
        </div>
    </div>
</section>

{{-- KEGIATAN / EVENTS --}}
<template x-if="events.length > 0">
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color:#019342">Agenda</p>
            <h2 class="text-2xl font-black text-gray-900">Kegiatan & Acara</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <template x-for="ev in events" :key="ev.id ?? ev.post_id">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 card-hover cursor-pointer"
                     @click="selectedPost = ev">
                    <div class="flex items-start gap-4">
                        <div class="rounded-xl p-3 shrink-0 text-white" style="background:#191654">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 text-sm line-clamp-2" x-text="ev.title"></p>
                            <p class="text-xs text-gray-400 mt-1" x-text="fmtDate(ev.event_start_at || ev.published_at || ev.created_at)"></p>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2" x-text="ev.summary || excerpt(ev.content, 80)"></p>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</section>
</template>

{{-- ARTIKEL TERBARU --}}
<template x-if="articles.length > 0">
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color:#019342">Artikel</p>
            <h2 class="text-2xl font-black text-gray-900">Artikel Terbaru</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <template x-for="art in articles" :key="art.id ?? art.post_id">
                <div class="rounded-2xl overflow-hidden shadow-sm border border-gray-100 card-hover cursor-pointer bg-white"
                     @click="selectedPost = art">
                    <template x-if="art.image || art.thumbnail">
                        <img :src="art.image || art.thumbnail" class="w-full h-48 object-cover bg-gray-100">
                    </template>
                    <div class="p-5">
                        <p class="text-xs text-gray-400 mb-2" x-text="fmtDateShort(art.published_at || art.created_at)"></p>
                        <h3 class="font-bold text-gray-900 text-sm line-clamp-2 mb-2" x-text="art.title"></h3>
                        <p class="text-xs text-gray-500 line-clamp-3" x-text="art.summary || excerpt(art.content)"></p>
                    </div>
                </div>
            </template>
        </div>
    </div>
</section>
</template>

{{-- TESTIMONI --}}
<template x-if="testimonials.length > 0">
<section class="py-16" style="background:linear-gradient(135deg,#0d1035,#191654)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color:#4ade80">Alumni Bicara</p>
            <h2 class="text-2xl font-black text-white">Testimoni & Cerita Sukses</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <template x-for="t in testimonials.slice(0,3)" :key="t.testimonial_id">
                <div class="rounded-2xl p-6" style="background:rgba(255,255,255,.07); backdrop-filter:blur(8px)">
                    <div class="flex gap-0.5 mb-3">
                        <template x-for="i in [1,2,3,4,5]">
                            <svg class="h-4 w-4" :class="i <= (t.rating ?? 5) ? 'text-yellow-400' : 'text-gray-600'" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </template>
                    </div>
                    <p class="text-gray-300 text-sm italic leading-relaxed mb-4" x-text="`"${t.content}"`"></p>
                    <div class="flex items-center gap-3">
                        <template x-if="t.photo">
                            <img :src="t.photo" class="h-10 w-10 rounded-full object-cover">
                        </template>
                        <template x-if="!t.photo">
                            <div class="h-10 w-10 rounded-full flex items-center justify-center text-white text-xs font-bold"
                                 style="background:#019342"
                                 x-text="t.name.split(' ').slice(0,2).map(n=>n[0]).join('').toUpperCase()"></div>
                        </template>
                        <div>
                            <p class="text-white font-semibold text-sm" x-text="t.name"></p>
                            <p class="text-gray-400 text-xs" x-text="[t.university, t.major].filter(Boolean).join(' — ') || t.role || ''"></p>
                            <p class="text-gray-500 text-xs" x-text="t.graduation_year ? 'Alumni ' + t.graduation_year : ''"></p>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</section>
</template>

{{-- CTA PPDB --}}
<section class="py-16 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <h2 class="text-2xl font-black text-gray-900 mb-3">Bergabunglah dengan SMA Al-Ghazaly</h2>
        <p class="text-gray-500 mb-8">Daftarkan putra-putri Anda sekarang dan raih masa depan gemilang bersama kami.</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <button @click="$store.pub.go('ppdb')"
                class="px-8 py-3.5 rounded-full text-white font-semibold text-sm hover:opacity-90 transition-all"
                style="background:#019342">
                Daftar PPDB Sekarang
            </button>
            <button @click="$store.pub.go('kontak')"
                class="px-8 py-3.5 rounded-full border-2 font-semibold text-sm transition-all hover:bg-gray-100"
                style="border-color:#191654; color:#191654">
                Hubungi Kami
            </button>
        </div>
    </div>
</section>

</div>
</template>

{{-- POST DETAIL MODAL --}}
<template x-teleport="body">
    <div x-show="selectedPost" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         @click.self="selectedPost = null" x-transition>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[85vh] overflow-y-auto" @click.stop>
            <template x-if="selectedPost && (selectedPost.image || selectedPost.thumbnail)">
                <img :src="selectedPost?.image || selectedPost?.thumbnail" class="w-full h-56 object-cover rounded-t-2xl">
            </template>
            <div class="p-6">
                <div class="flex items-start justify-between gap-4 mb-3">
                    <h2 class="font-bold text-gray-900 text-lg leading-tight" x-text="selectedPost?.title"></h2>
                    <button @click="selectedPost = null" class="shrink-0 p-1 text-gray-400 hover:text-gray-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <p class="text-xs text-gray-400 mb-4" x-text="fmtDate(selectedPost?.published_at || selectedPost?.created_at)"></p>
                <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-line" x-text="selectedPost?.content"></div>
            </div>
        </div>
    </div>
</template>
