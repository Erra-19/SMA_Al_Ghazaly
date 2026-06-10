{{-- ═══ PENGAJAR ═══════════════════════════════════════════════════════════════ --}}

<div class="py-14 text-white text-center" style="background:linear-gradient(135deg,#0d1035,#191654)">
    <div class="max-w-3xl mx-auto px-4">
        <p class="text-xs font-semibold uppercase tracking-widest mb-2" style="color:#4ade80">Tim Pendidik</p>
        <h1 class="text-3xl font-black mb-3">Tenaga Pengajar</h1>
        <p class="text-gray-300">Didukung oleh para pendidik berpengalaman, berdedikasi, dan berkomitmen tinggi</p>
    </div>
</div>

{{-- LOADING --}}
<div x-show="loading" class="py-24 flex justify-center">
    <svg class="spinner h-8 w-8" style="color:#019342" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
    </svg>
</div>

<div x-show="!loading" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
        <div class="rounded-2xl p-5 text-center bg-green-50 border border-green-100">
            <p class="text-3xl font-black" style="color:#019342" x-text="stats.total"></p>
            <p class="text-gray-600 text-xs mt-1">Total Pengajar</p>
        </div>
        <div class="rounded-2xl p-5 text-center bg-blue-50 border border-blue-100">
            <p class="text-3xl font-black text-blue-700" x-text="stats.imtak"></p>
            <p class="text-gray-600 text-xs mt-1">Imtak & Keagamaan</p>
        </div>
        <div class="rounded-2xl p-5 text-center bg-purple-50 border border-purple-100">
            <p class="text-3xl font-black text-purple-700" x-text="stats.mipa"></p>
            <p class="text-gray-600 text-xs mt-1">MIPA & Teknologi</p>
        </div>
        <div class="rounded-2xl p-5 text-center bg-amber-50 border border-amber-100">
            <p class="text-3xl font-black text-amber-700" x-text="stats.lainnya"></p>
            <p class="text-gray-600 text-xs mt-1">IPS, Bahasa & Staf</p>
        </div>
    </div>

    {{-- Pimpinan / Direksi --}}
    <template x-if="leadership.length > 0">
    <section class="mb-12">
        <h2 class="text-xl font-black text-gray-900 mb-6">Pimpinan Sekolah</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
            <template x-for="t in leadership" :key="t.teacher_id">
                <div class="bg-white rounded-2xl p-4 text-center shadow-sm border border-gray-100 card-hover cursor-pointer"
                     @click="selectedTeacher = t">
                    <template x-if="t.photo">
                        <img :src="t.photo" class="h-20 w-20 rounded-full object-cover mx-auto mb-3 bg-gray-100">
                    </template>
                    <template x-if="!t.photo">
                        <div class="h-20 w-20 rounded-full mx-auto mb-3 flex items-center justify-center text-white text-xl font-black"
                             style="background:#019342"
                             x-text="initials(t.name)"></div>
                    </template>
                    <p class="font-bold text-gray-900 text-sm line-clamp-1" x-text="t.name"></p>
                    <p class="text-xs text-gray-500 line-clamp-2 mt-0.5" x-text="t.position || t.subject || ''"></p>
                </div>
            </template>
        </div>
    </section>
    </template>

    {{-- Filter & Search --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-8">
        <input x-model="search" type="text" placeholder="Cari nama atau mata pelajaran..."
            class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
            style="--tw-ring-color:#019342">
        <div class="flex gap-2 flex-wrap">
            <template x-for="cat in categories" :key="cat.key">
                <button @click="filterCat = cat.key"
                    :class="filterCat === cat.key
                        ? 'text-white'
                        : 'bg-white text-gray-600 border border-gray-200 hover:border-green-300'"
                    :style="filterCat === cat.key ? 'background:#019342' : ''"
                    class="px-3 py-1.5 rounded-xl text-xs font-medium transition-all"
                    x-text="cat.label">
                </button>
            </template>
        </div>
    </div>

    {{-- Teacher Grid --}}
    <template x-if="filtered.length === 0">
        <div class="py-16 text-center text-gray-400">
            <svg class="h-12 w-12 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <p>Tidak ada guru ditemukan.</p>
        </div>
    </template>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        <template x-for="t in filtered" :key="t.teacher_id">
            <div class="bg-white rounded-2xl p-4 text-center shadow-sm border border-gray-100 card-hover cursor-pointer"
                 @click="selectedTeacher = t">
                <template x-if="t.photo">
                    <img :src="t.photo" class="h-16 w-16 rounded-full object-cover mx-auto mb-3 bg-gray-100">
                </template>
                <template x-if="!t.photo">
                    <div class="h-16 w-16 rounded-full mx-auto mb-3 flex items-center justify-center text-white font-bold"
                         :style="'background:' + (['#019342','#191654','#0369a1','#b45309'][t.teacher_id % 4])"
                         x-text="initials(t.name)"></div>
                </template>
                <p class="font-semibold text-gray-900 text-xs line-clamp-2" x-text="t.name"></p>
                <p class="text-xs text-gray-500 mt-0.5 line-clamp-1" x-text="t.subject || t.position || ''"></p>
                <template x-if="t.experience">
                    <p class="text-xs mt-1 font-medium" style="color:#019342" x-text="t.experience"></p>
                </template>
            </div>
        </template>
    </div>

</div>

{{-- Teacher Detail Modal --}}
<template x-teleport="body">
    <div x-show="selectedTeacher" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         @click.self="selectedTeacher = null" x-transition>
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto" @click.stop>
            <div class="p-6">
                {{-- Header --}}
                <div class="flex items-start justify-between gap-4 mb-5">
                    <div class="flex items-center gap-4">
                        <template x-if="selectedTeacher?.photo">
                            <img :src="selectedTeacher.photo" class="h-16 w-16 rounded-full object-cover bg-gray-100 shrink-0">
                        </template>
                        <template x-if="!selectedTeacher?.photo">
                            <div class="h-16 w-16 rounded-full flex items-center justify-center text-white font-black text-xl shrink-0"
                                 style="background:#019342"
                                 x-text="initials(selectedTeacher?.name ?? '')"></div>
                        </template>
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg" x-text="selectedTeacher?.name"></h3>
                            <p class="text-sm text-gray-500" x-text="selectedTeacher?.position || selectedTeacher?.subject || ''"></p>
                            <template x-if="selectedTeacher?.experience">
                                <p class="text-xs font-medium mt-0.5" style="color:#019342" x-text="selectedTeacher?.experience"></p>
                            </template>
                        </div>
                    </div>
                    <button @click="selectedTeacher = null" class="p-1 text-gray-400 hover:text-gray-600 shrink-0">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Education --}}
                <template x-if="selectedTeacher?.education">
                    <div class="mb-4 p-3 bg-blue-50 rounded-xl">
                        <p class="text-xs font-semibold text-blue-700 mb-1">Pendidikan</p>
                        <p class="text-sm text-gray-700" x-text="selectedTeacher?.education"></p>
                    </div>
                </template>

                {{-- Bio --}}
                <template x-if="selectedTeacher?.bio">
                    <p class="text-sm text-gray-600 leading-relaxed mb-4" x-text="selectedTeacher?.bio"></p>
                </template>

                {{-- Philosophy --}}
                <template x-if="selectedTeacher?.philosophy">
                    <blockquote class="border-l-4 pl-4 mb-4 italic text-sm text-gray-600" style="border-color:#019342"
                                x-text="`"${selectedTeacher?.philosophy}"`">
                    </blockquote>
                </template>

                {{-- Tags --}}
                <template x-if="tagsArray(selectedTeacher?.tags).length > 0">
                    <div class="flex flex-wrap gap-2 mb-4">
                        <template x-for="tag in tagsArray(selectedTeacher?.tags)">
                            <span class="px-3 py-1 rounded-full text-xs font-medium text-white" style="background:#019342"
                                  x-text="tag"></span>
                        </template>
                    </div>
                </template>

                {{-- Contact --}}
                <div class="space-y-2 pt-3 border-t border-gray-100">
                    <template x-if="selectedTeacher?.subject">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="h-4 w-4" style="color:#019342" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            <span x-text="'Mengajar: ' + selectedTeacher?.subject"></span>
                        </div>
                    </template>
                    <template x-if="selectedTeacher?.email">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="h-4 w-4" style="color:#019342" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span x-text="selectedTeacher?.email"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
