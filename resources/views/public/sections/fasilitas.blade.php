{{-- ═══ FASILITAS ══════════════════════════════════════════════════════════════ --}}

<div class="py-14 text-white text-center" style="background:linear-gradient(135deg,#0d1035,#191654)">
    <div class="max-w-3xl mx-auto px-4">
        <p class="text-xs font-semibold uppercase tracking-widest mb-2" style="color:#4ade80">Sarana & Prasarana</p>
        <h1 class="text-3xl font-black mb-3">Fasilitas Sekolah</h1>
        <p class="text-gray-300">Lingkungan belajar yang kondusif dengan fasilitas modern dan lengkap</p>
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

    {{-- Highlight fasilitas unggulan --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
        @foreach([['🏫','Gedung Belajar','Ruangan ber-AC & modern'],['🔬','Laboratorium','Lab Fisika, Kimia, Biologi, IT'],['📚','Perpustakaan','Koleksi 10.000+ buku'],['🕌','Musholla','Masjid kapasitas 500 jamaah']] as $f)
        <div class="rounded-2xl p-5 text-center bg-gray-50 border border-gray-100">
            <div class="text-4xl mb-2">{{ $f[0] }}</div>
            <p class="font-bold text-gray-900 text-sm">{{ $f[1] }}</p>
            <p class="text-gray-500 text-xs mt-1">{{ $f[2] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Album list --}}
    <template x-if="albums.length === 0 && !loading">
        <div class="py-16 text-center">
            <svg class="h-16 w-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <p class="text-gray-400">Galeri foto akan segera tersedia.</p>
        </div>
    </template>

    <template x-if="albums.length > 0">
    <div>
        <h2 class="text-xl font-black text-gray-900 mb-6">Galeri Foto</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <template x-for="album in albums" :key="album.album_id">
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 card-hover cursor-pointer"
                     @click="openAlbum(album)">
                    <template x-if="album.cover">
                        <img :src="album.cover" class="w-full h-48 object-cover bg-gray-100">
                    </template>
                    <template x-if="!album.cover">
                        <div class="w-full h-48 bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center">
                            <svg class="h-14 w-14" style="color:#bbf7d0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    </template>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 text-sm" x-text="album.title"></h3>
                        <p x-show="album.description" class="text-xs text-gray-500 mt-1 line-clamp-2" x-text="album.description"></p>
                        <p class="text-xs mt-2 font-medium" style="color:#019342">Lihat Foto →</p>
                    </div>
                </div>
            </template>
        </div>
    </div>
    </template>

</div>

{{-- Album Detail Modal --}}
<template x-teleport="body">
    <div x-show="selectedAlbum" class="fixed inset-0 z-50 flex items-start justify-center p-4 pt-10 bg-black/70 backdrop-blur-sm"
         @click.self="selectedAlbum = null; albumMedia = []" x-transition>
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[85vh] overflow-y-auto" @click.stop>
            <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-900" x-text="selectedAlbum?.title"></h2>
                <button @click="selectedAlbum = null; albumMedia = []" class="p-1 text-gray-400 hover:text-gray-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6">
                <div x-show="albumLoading" class="py-12 flex justify-center">
                    <svg class="spinner h-6 w-6" style="color:#019342" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                    </svg>
                </div>
                <template x-if="!albumLoading && albumMedia.length === 0">
                    <p class="text-center text-gray-400 py-8">Belum ada foto di album ini.</p>
                </template>
                <div x-show="!albumLoading && albumMedia.length > 0"
                     class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <template x-for="media in albumMedia" :key="media.media_id">
                        <div class="relative group cursor-pointer" @click="lightboxImg = (media.path ?? media.filename)">
                            <img :src="media.path ?? media.filename"
                                 class="w-full h-36 object-cover rounded-xl bg-gray-100 group-hover:brightness-90 transition-all">
                            <div class="absolute inset-0 rounded-xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black/30">
                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    {{-- Lightbox --}}
    <div x-show="lightboxImg" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/90"
         @click="lightboxImg = null" x-transition>
        <img :src="lightboxImg" class="max-w-full max-h-full rounded-lg shadow-2xl object-contain p-4">
        <button class="absolute top-4 right-4 text-white p-2 hover:bg-white/10 rounded-full">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
</template>
